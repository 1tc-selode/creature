<?php

namespace Database\Seeders;

use App\Models\Kepesseg;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KepessegSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kepessegek = [
            // Fizikai képességek
            [
                'nev' => 'Szupergyorsaság',
                'leiras' => 'Rendkívül gyors mozgás és reflexek',
                'tipus' => 'fizikai',
                'erosseg' => 8,
                'ritkasag' => 7
            ],
            [
                'nev' => 'Szuperierő',
                'leiras' => 'Emberi képességeket messze meghaladó fizikai erő',
                'tipus' => 'fizikai',
                'erosseg' => 9,
                'ritkasag' => 6
            ],
            [
                'nev' => 'Repülés',
                'leiras' => 'Gravitáció feletti uralkodás és szabad repülés',
                'tipus' => 'fizikai',
                'erosseg' => 7,
                'ritkasag' => 8
            ],
            [
                'nev' => 'Láthatatlanság',
                'leiras' => 'Fény manipulálása a láthatatlanság érdekében',
                'tipus' => 'fizikai',
                'erosseg' => 6,
                'ritkasag' => 9
            ],
            [
                'nev' => 'Átváltozás',
                'leiras' => 'Fizikai forma megváltoztatásának képessége',
                'tipus' => 'fizikai',
                'erosseg' => 8,
                'ritkasag' => 8
            ],

            // Mágikus képességek
            [
                'nev' => 'Tűzvarázslat',
                'leiras' => 'Tűz létrehozása és irányítása',
                'tipus' => 'mágikus',
                'erosseg' => 7,
                'ritkasag' => 5
            ],
            [
                'nev' => 'Jégvarázslat',
                'leiras' => 'Jég és hideg irányítása',
                'tipus' => 'mágikus',
                'erosseg' => 6,
                'ritkasag' => 5
            ],
            [
                'nev' => 'Villamvarázslat',
                'leiras' => 'Elektromosság generálása és irányítása',
                'tipus' => 'mágikus',
                'erosseg' => 8,
                'ritkasag' => 6
            ],
            [
                'nev' => 'Teleportáció',
                'leiras' => 'Azonnali térbeli áthelyeződés',
                'tipus' => 'mágikus',
                'erosseg' => 9,
                'ritkasag' => 9
            ],
            [
                'nev' => 'Gyógyítás',
                'leiras' => 'Sebek és betegségek mágikus gyógyítása',
                'tipus' => 'mágikus',
                'erosseg' => 7,
                'ritkasag' => 7
            ],

            // Mentális képességek
            [
                'nev' => 'Telepátia',
                'leiras' => 'Gondolatok olvasása és továbbítása',
                'tipus' => 'mentális',
                'erosseg' => 6,
                'ritkasag' => 8
            ],
            [
                'nev' => 'Telekinézis',
                'leiras' => 'Tárgyak mozgatása elme erejével',
                'tipus' => 'mentális',
                'erosseg' => 7,
                'ritkasag' => 7
            ],
            [
                'nev' => 'Jövőlátás',
                'leiras' => 'Jövőbeli események előrelátása',
                'tipus' => 'mentális',
                'erosseg' => 8,
                'ritkasag' => 10
            ],
            [
                'nev' => 'Memória manipuláció',
                'leiras' => 'Mások emlékeinek módosítása',
                'tipus' => 'mentális',
                'erosseg' => 8,
                'ritkasag' => 9
            ],
            [
                'nev' => 'Illúzió teremtés',
                'leiras' => 'Hamis érzékelések létrehozása',
                'tipus' => 'mentális',
                'erosseg' => 6,
                'ritkasag' => 6
            ],

            // Speciális képességek
            [
                'nev' => 'Időmanipuláció',
                'leiras' => 'Az idő folyásának befolyásolása',
                'tipus' => 'speciális',
                'erosseg' => 10,
                'ritkasag' => 10
            ],
            [
                'nev' => 'Dimenzióváltás',
                'leiras' => 'Ugrás más dimenziókba',
                'tipus' => 'speciális',
                'erosseg' => 9,
                'ritkasag' => 10
            ],
            [
                'nev' => 'Anyagátalakítás',
                'leiras' => 'Molekuláris szerkezet megváltoztatása',
                'tipus' => 'speciális',
                'erosseg' => 8,
                'ritkasag' => 9
            ],
            [
                'nev' => 'Energia elszívás',
                'leiras' => 'Mások életereje felszívása',
                'tipus' => 'speciális',
                'erosseg' => 7,
                'ritkasag' => 8
            ],
            [
                'nev' => 'Hangulatbefolyásolás',
                'leiras' => 'Mások érzelmeinek irányítása',
                'tipus' => 'speciális',
                'erosseg' => 5,
                'ritkasag' => 4
            ],
            [
                'nev' => 'Természet irányítás',
                'leiras' => 'Növények és állatok irányítása',
                'tipus' => 'speciális',
                'erosseg' => 6,
                'ritkasag' => 6
            ]
        ];

        foreach ($kepessegek as $kepesseg) {
            Kepesseg::create($kepesseg);
        }
    }
}
