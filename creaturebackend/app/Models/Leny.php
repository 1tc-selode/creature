<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Leny extends Model
{
    protected $table = 'lenyek';

    protected $fillable = [
        'nev',
        'tudomanyos_nev',
        'leiras',
        'elohely',
        'meret',
        'veszelyesseg',
        'ritkasag',
        'felfedezes_datuma',
        'felfedezo',
        'allapot',
        'extra_adatok',
        'kep_url',
        'user_id',
        'kategoria_id'
    ];

    protected $casts = [
        'ritkasag' => 'integer',
        'felfedezes_datuma' => 'date',
        'extra_adatok' => 'array',
    ];

    /**
     * A lény tulajdonosa (felhasználó)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A lény kategóriája
     */
    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class);
    }

    /**
     * Lény képességei (N:N kapcsolat)
     */
    public function kepessegek(): BelongsToMany
    {
        return $this->belongsToMany(Kepesseg::class, 'leny_kepesseg')
            ->withPivot(['szint', 'megjegyzes'])
            ->withTimestamps();
    }

    /**
     * Lényhez tartozó galériakepek
     */
    public function galeriakepek(): HasMany
    {
        return $this->hasMany(Galeriakep::class);
    }

    /**
     * Lényhez tartozó események
     */
    public function esemenyek(): HasMany
    {
        return $this->hasMany(Esemeny::class);
    }

    /**
     * Lényhez kapcsolódó üzenetek
     */
    public function kapcsolatiUzenetek(): HasMany
    {
        return $this->hasMany(KapcsolatiUzenet::class);
    }

    /**
     * Csak aktív lények
     */
    public function scopeAktiv($query)
    {
        return $query->where('allapot', 'aktív');
    }

    /**
     * Lények méret szerint
     */
    public function scopeMeret($query, $meret)
    {
        return $query->where('meret', $meret);
    }

    /**
     * Lények veszélyesség szerint
     */
    public function scopeVeszelyesseg($query, $veszelyesseg)
    {
        return $query->where('veszelyesseg', $veszelyesseg);
    }

    /**
     * Főkép accessor
     */
    public function getFoKepAttribute()
    {
        $foKep = $this->galeriakepek()->where('fo_kep', true)->first();
        return $foKep ? $foKep->fajl_utvonal : $this->kep_url;
    }
}
