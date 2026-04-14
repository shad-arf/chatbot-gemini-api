<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'bot_key' => 'required|string',
            'history' => 'nullable|array',
            'history.*.role' => 'required|string|in:user,model',
            'history.*.parts' => 'required|array',
            'history.*.parts.*.text' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:20480',
        ];
    }
}
