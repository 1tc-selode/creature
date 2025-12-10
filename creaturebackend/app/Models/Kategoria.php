<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategoria extends Model
{
    protected $table = 'kategoriak';

    protected $fillable = [
        'nev',
        'leiras',
        'szin',
        'ikon',
        'aktiv'
    ];

    protected $casts = [
        'aktiv' => 'boolean',
    ];

    /**
     * Egy kategóriához tartozó lények
     */
    public function lenyek(): HasMany
    {
        return $this->hasMany(Leny::class);
    }

    /**
     * Csak aktív kategóriák
     */
    public function scopeAktiv($query)
    {
        return $query->where('aktiv', true);
    }
}
