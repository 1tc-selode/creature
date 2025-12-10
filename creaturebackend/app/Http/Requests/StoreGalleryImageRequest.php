<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'leiras' => 'nullable|string|max:1000',
            'fo_kep' => 'boolean',
            'sorrend' => 'nullable|integer|min:0',
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
            'image.required' => 'A kép feltöltése kötelező.',
            'image.image' => 'Csak képfájlok tölthetők fel.',
            'image.mimes' => 'Támogatott formátumok: jpeg, png, jpg, gif, webp.',
            'image.max' => 'A kép mérete legfeljebb 5MB lehet.',
            'leiras.max' => 'A leírás legfeljebb 1000 karakter lehet.',
            'fo_kep.boolean' => 'A főkép mező csak igaz/hamis értéket tartalmazhat.',
            'sorrend.integer' => 'A sorrend csak szám lehet.',
            'sorrend.min' => 'A sorrend nem lehet negatív.',
        ];
    }
}
