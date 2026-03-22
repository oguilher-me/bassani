<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable', 
                'email', 
                Rule::unique('crm_leads')->where(function ($query) {
                    return $query->whereIn('status', ['new', 'contacted']);
                })
            ],
            'phone' => [
                'nullable', 
                'string', 
                Rule::unique('crm_leads')->where(function ($query) {
                    return $query->whereIn('status', ['new', 'contacted']);
                })
            ],
            'type' => 'required|in:PF,PJ',
            'status' => 'required|in:new,contacted,qualified,converted,discarded',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Já existe um Lead ativo (Novo ou Contatado) com este e-mail.',
            'phone.unique' => 'Já existe um Lead ativo (Novo ou Contatado) com este telefone.',
        ];
    }
}
