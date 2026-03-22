<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArchitectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:CAU,ABD,CREA',
            'document_number' => ['required', 'string', Rule::unique('architects')->ignore($this->architect)],
            'specialty' => 'nullable|string|max:255',
            'rt_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'boolean',
            'rating' => 'nullable|integer|min:1|max:5',
            'bank_data.bank' => 'nullable|string',
            'bank_data.agency' => 'nullable|string',
            'bank_data.account' => 'nullable|string',
            'bank_data.pix' => 'nullable|string',
            'social_links.instagram' => 'nullable|string',
            'social_links.portfolio' => 'nullable|url',
        ];
    }
}
