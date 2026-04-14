<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'instruction_files' => 'nullable|array',
            'instruction_files.*' => 'file|mimes:pdf|max:20480',
            'origin_url' => 'nullable|url',
        ];
    }
}
