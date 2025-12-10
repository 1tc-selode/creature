<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Esemeny extends Model
{
    protected $table = 'esemenyek';

    protected $fillable = [
        'leny_id',
        'user_id',
        'cim',
        'leiras',
        'tipus',
        'esemeny_datuma',
        'helyszin',
        'koordinatak',
        'fontossag',
        'tanusitva',
        'csatolmanyok',
        'publikus'
    ];

    protected $casts = [
        'esemeny_datuma' => 'datetime',
        'koordinatak' => 'array',
        'csatolmanyok' => 'array',
        'publikus' => 'boolean',
    ];

    /**
     * Az eseményhez tartozó lény
     */
    public function leny(): BelongsTo
    {
        return $this->belongsTo(Leny::class);
    }

    /**
     * Az esemény rögzítője
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Csak nyilvános események
     */
    public function scopePublikus($query)
    {
        return $query->where('publikus', true);
    }

    /**
     * Típus szerint
     */
    public function scopeTipus($query, $tipus)
    {
        return $query->where('tipus', $tipus);
    }

    /**
     * Fontosság szerint
     */
    public function scopeFontossag($query, $fontossag)
    {
        return $query->where('fontossag', $fontossag);
    }

    /**
     * Dátum szerint rendezve
     */
    public function scopeKronologikus($query)
    {
        return $query->orderBy('esemeny_datuma', 'desc');
    }
}
