<?php

namespace App\Http\Controllers\Web;

use App\Models\Bot;
use Illuminate\Http\Request;

class UserChatController
{
    public function index()
    {
        $bot = Bot::where('is_default', true)->first();

        if (!$bot) {
            return view('chat', ['bot' => null, 'error' => 'No chatbot is currently available.']);
        }

        return view('chat', ['bot' => $bot]);
    }
}
