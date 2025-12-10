<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreatureRequest extends FormRequest
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
            'nev' => 'required|string|max:200',
            'tudomanyos_nev' => 'nullable|string|max:300',
            'leiras' => 'required|string|max:10000',
            'elohely' => 'nullable|string|max:1000',
            'meret' => 'required|in:apró,kicsi,közepes,nagy,hatalmas,gigantikus',
            'veszelyesseg' => 'required|in:ártalmatlan,alacsony,közepes,magas,extrém',
            'ritkasag' => 'required|integer|between:1,10',
            'felfedezes_datuma' => 'nullable|date',
            'felfedezo' => 'nullable|string|max:200',
            'allapot' => 'required|in:aktív,inaktív,kivizsgálás_alatt,eltűnt',
            'kategoria_id' => 'required|exists:kategoriak,id',
            'kep_url' => 'nullable|url|max:500',
            'kep' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'extra_adatok' => 'nullable|array',
            'kepessegek' => 'nullable|array',
            'kepessegek.*' => 'exists:kepessegek,id',
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
            'nev.required' => 'A lény nevének megadása kötelező.',
            'nev.max' => 'A lény neve legfeljebb 200 karakter lehet.',
            'leiras.required' => 'A lény leírásának megadása kötelező.',
            'kategoria_id.required' => 'A kategória megadása kötelező.',
            'kategoria_id.exists' => 'A megadott kategória nem létezik.',
            'ritkasag.between' => 'A ritkaság 1-10 közötti érték lehet.',
            'meret.in' => 'Érvénytelen méret érték.',
            'veszelyesseg.in' => 'Érvénytelen veszélyesség érték.',
            'allapot.in' => 'Érvénytelen állapot érték.',
            'kepessegek.*.exists' => 'Az egyik megadott képesség nem létezik.',
        ];
    }
}
