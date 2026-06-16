<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'competences_requises' => ['required', 'array'],
            'competences_requises.*' => ['required', 'string', 'max:100'],
            'experience_min' => ['required', 'integer', 'min:0', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'competences_requises.required' => 'Ajoutez au moins une compétence.',
        ];
    }
}
