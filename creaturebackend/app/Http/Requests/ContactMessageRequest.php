<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Nyilvános űrlap
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nev' => 'required|string|max:200',
            'email' => 'required|email|max:255',
            'telefon' => 'nullable|string|max:20',
            'targy' => 'required|string|max:300',
            'uzenet' => 'required|string|max:5000',
            'tipus' => 'required|in:általános,lény_bejelentés,hiba_jelentés,javaslat,egyéb',
            'leny_id' => 'nullable|exists:lenyek,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nev.required' => 'A név megadása kötelező.',
            'nev.max' => 'A név legfeljebb 200 karakter lehet.',
            'email.required' => 'Az email cím megadása kötelező.',
            'email.email' => 'Érvényes email címet adjon meg.',
            'email.max' => 'Az email cím legfeljebb 255 karakter lehet.',
            'telefon.max' => 'A telefonszám legfeljebb 20 karakter lehet.',
            'targy.required' => 'A tárgy megadása kötelező.',
            'targy.max' => 'A tárgy legfeljebb 300 karakter lehet.',
            'uzenet.required' => 'Az üzenet megadása kötelező.',
            'uzenet.max' => 'Az üzenet legfeljebb 5000 karakter lehet.',
            'tipus.required' => 'Az üzenet típusának megadása kötelező.',
            'tipus.in' => 'Érvénytelen üzenet típus.',
            'leny_id.exists' => 'A megadott lény nem létezik.',
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'ip_cim' => $this->ip(),
            'user_agent' => $this->header('User-Agent'),
        ]);
    }
}
