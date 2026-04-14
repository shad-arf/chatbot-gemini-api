<?php

namespace App\Http\Controllers\Web;

use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        if ($username === $adminUsername && $password === $adminPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid username or password.']);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login.show');
    }

    public function dashboard()
    {
        $bots = Bot::all();
        $defaultBot = Bot::where('is_default', true)->first();
        return view('admin.dashboard', ['bots' => $bots, 'defaultBot' => $defaultBot]);
    }

    public function edit(Bot $bot)
    {
        return view('admin.edit', ['bot' => $bot]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'instruction_files' => 'nullable|array',
            'instruction_files.*' => 'file|mimes:pdf|max:20480',
            'origin_url' => 'nullable|url',
        ]);

        $bot = new Bot();
        $bot->name = $validated['name'];
        $bot->instruction = $validated['instruction'] ?? '';
        $bot->origin_url = $validated['origin_url'] ?? null;

        // Handle file uploads
        if ($request->hasFile('instruction_files')) {
            $files = [];
            foreach ($request->file('instruction_files') as $file) {
                $path = $file->store('bot-instructions', 'public');
                $files[] = [
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $bot->instruction_files = $files;
        }

        $bot->save();

        return redirect()->route('admin.dashboard')->with('success', "Bot '{$bot->name}' created successfully.");
    }

    public function update(Request $request, Bot $bot)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'instruction' => 'nullable|string',
            'instruction_files' => 'nullable|array',
            'instruction_files.*' => 'file|mimes:pdf|max:20480',
            'origin_url' => 'nullable|url',
        ]);

        if (isset($validated['name'])) {
            $bot->name = $validated['name'];
        }

        if (isset($validated['instruction'])) {
            $bot->instruction = $validated['instruction'];
        }

        if (isset($validated['origin_url'])) {
            $bot->origin_url = $validated['origin_url'];
        }

        // Handle file uploads (replace old files)
        if ($request->hasFile('instruction_files')) {
            // Delete old files
            if ($bot->instruction_files) {
                foreach ($bot->instruction_files as $file) {
                    Storage::disk('public')->delete($file['path']);
                }
            }

            // Store new files
            $files = [];
            foreach ($request->file('instruction_files') as $file) {
                $path = $file->store('bot-instructions', 'public');
                $files[] = [
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $bot->instruction_files = $files;
        }

        $bot->save();

        return redirect()->route('admin.dashboard')->with('success', "Bot '{$bot->name}' updated successfully.");
    }

    public function destroy(Bot $bot)
    {
        $name = $bot->name;

        // Delete instruction files
        if ($bot->instruction_files) {
            foreach ($bot->instruction_files as $file) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        // Reset default if this was the default bot
        if ($bot->is_default) {
            $bot->is_default = false;
        }

        $bot->delete();

        return redirect()->route('admin.dashboard')->with('success', "Bot '{$name}' deleted successfully.");
    }

    public function setDefault(Bot $bot)
    {
        // Remove default from all other bots
        Bot::where('is_default', true)->update(['is_default' => false]);

        // Set this bot as default
        $bot->is_default = true;
        $bot->save();

        return redirect()->route('admin.dashboard')->with('success', "Bot '{$bot->name}' set as default.");
    }
}
