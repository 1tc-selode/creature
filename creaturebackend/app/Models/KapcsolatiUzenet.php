<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KapcsolatiUzenet extends Model
{
    protected $table = 'kapcsolati_uzenetek';

    protected $fillable = [
        'nev',
        'email',
        'telefon',
        'targy',
        'uzenet',
        'tipus',
        'leny_id',
        'allapot',
        'admin_megjegyzes',
        'ip_cim',
        'user_agent'
    ];

    /**
     * Kapcsolódó lény (ha van)
     */
    public function leny(): BelongsTo
    {
        return $this->belongsTo(Leny::class);
    }

    /**
     * Új üzenetek
     */
    public function scopeUj($query)
    {
        return $query->where('allapot', 'új');
    }

    /**
     * Állapot szerint
     */
    public function scopeAllapot($query, $allapot)
    {
        return $query->where('allapot', $allapot);
    }

    /**
     * Típus szerint
     */
    public function scopeTipus($query, $tipus)
    {
        return $query->where('tipus', $tipus);
    }

    /**
     * Legutóbbi üzenetek
     */
    public function scopeLegujabb($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
