<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'instruction' => 'sometimes|string',
            'instruction_files' => 'sometimes|nullable|array',
            'instruction_files.*' => 'file|mimes:pdf|max:20480',
            'origin_url' => 'sometimes|nullable|url',
        ];
    }
}
