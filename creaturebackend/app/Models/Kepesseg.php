<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kepesseg extends Model
{
    protected $table = 'kepessegek';

    protected $fillable = [
        'nev',
        'leiras',
        'tipus',
        'erosseg',
        'ritkasag',
        'aktiv'
    ];

    protected $casts = [
        'erosseg' => 'integer',
        'ritkasag' => 'integer',
        'aktiv' => 'boolean',
    ];

    /**
     * Képességhez tartozó lények (N:N kapcsolat)
     */
    public function lenyek(): BelongsToMany
    {
        return $this->belongsToMany(Leny::class, 'leny_kepesseg')
            ->withPivot(['szint', 'megjegyzes'])
            ->withTimestamps();
    }

    /**
     * Csak aktív képességek
     */
    public function scopeAktiv($query)
    {
        return $query->where('aktiv', true);
    }

    /**
     * Képességek típus szerint
     */
    public function scopeTipus($query, $tipus)
    {
        return $query->where('tipus', $tipus);
    }
}
