<?php

namespace Database\Seeders;

use App\Models\Kategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriak = [
            [
                'nev' => 'Mágikus Lények',
                'leiras' => 'Varázserővel rendelkező, természetfeletti képességű lények',
                'szin' => '#8b5cf6',
                'ikon' => 'magic-wand'
            ],
            [
                'nev' => 'Mutáns Állatok',
                'leiras' => 'Genetikai mutáción átesett, különleges tulajdonságú állatok',
                'szin' => '#10b981',
                'ikon' => 'dna'
            ],
            [
                'nev' => 'Digitális Lények',
                'leiras' => 'Számítógépes környezetben élő, mesterséges intelligenciával rendelkező entitások',
                'szin' => '#06b6d4',
                'ikon' => 'microchip'
            ],
            [
                'nev' => 'Időutazó Fajták',
                'leiras' => 'Különböző időperiódusokból származó vagy időmanipulációra képes lények',
                'szin' => '#f59e0b',
                'ikon' => 'clock'
            ],
            [
                'nev' => 'Dimenzióközi Lények',
                'leiras' => 'Más dimenziókból származó vagy dimenziók között utazni képes entitások',
                'szin' => '#ec4899',
                'ikon' => 'portal'
            ],
            [
                'nev' => 'Hibrid Lények',
                'leiras' => 'Több faj kereszteződéséből született különleges tulajdonságú lények',
                'szin' => '#ef4444',
                'ikon' => 'shuffle'
            ],
            [
                'nev' => 'Láthatatlan Fajok',
                'leiras' => 'Láthatatlanságra vagy álcázásra specializálódott rejtélyes lények',
                'szin' => '#6b7280',
                'ikon' => 'eye-slash'
            ],
            [
                'nev' => 'Energialények',
                'leiras' => 'Tiszta energiából álló vagy energiával tápláló különleges entitások',
                'szin' => '#fbbf24',
                'ikon' => 'bolt'
            ],
        ];

        foreach ($kategoriak as $kategoria) {
            Kategoria::create($kategoria);
        }
    }
}
