<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeriakep extends Model
{
    protected $table = 'galeriakepek';

    protected $fillable = [
        'leny_id',
        'eredeti_nev',
        'fajl_nev',
        'fajl_utvonal',
        'mime_tipus',
        'fajl_meret',
        'leiras',
        'sorrend',
        'fo_kep'
    ];

    protected $casts = [
        'fajl_meret' => 'integer',
        'sorrend' => 'integer',
        'fo_kep' => 'boolean',
    ];

    /**
     * A képhez tartozó lény
     */
    public function leny(): BelongsTo
    {
        return $this->belongsTo(Leny::class);
    }

    /**
     * Csak főképek
     */
    public function scopeFoKep($query)
    {
        return $query->where('fo_kep', true);
    }

    /**
     * Sorrend szerint rendezve
     */
    public function scopeRendezett($query)
    {
        return $query->orderBy('sorrend')->orderBy('created_at');
    }

    /**
     * Fájl méret ember által olvasható formátumban
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->fajl_meret;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
