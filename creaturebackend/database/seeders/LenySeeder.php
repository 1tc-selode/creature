<?php

namespace Database\Seeders;

use App\Models\Leny;
use App\Models\Kategoria;
use App\Models\Kepesseg;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LenySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Először létrehozunk egy admin felhasználót
        $admin = User::firstOrCreate(
            ['email' => 'admin@külkat.hu'],
            [
                'name' => 'KÜLKAT Admin',
                'password' => bcrypt('password123')
            ]
        );

        $lenyek = [
            [
                'nev' => 'Teleportáló Teve',
                'tudomanyos_nev' => 'Camelus teleporticus',
                'leiras' => 'Egy különleges teve fajta, amely képes térbeli ugrásokra rövid távon. Főleg sivatagi környezetben él, de képes azonnal eltűnni és máshol megjelenni, ha veszélyt érez. Különösen szereti a datolyát és a mágikus oázisokat.',
                'elohely' => 'Szahara sivatag, mágikus oázisok környékén',
                'meret' => 'nagy',
                'veszelyesseg' => 'alacsony',
                'ritkasag' => 7,
                'felfedezes_datuma' => '2023-03-15',
                'felfedezo' => 'Dr. Hassan al-Mágikus',
                'allapot' => 'aktív',
                'kepessegek' => [9] // Teleportáció
            ],
            [
                'nev' => 'Hangulatváltós Kaktusz',
                'tudomanyos_nev' => 'Cactus emotionalis',
                'leiras' => 'Rendkívül érdekes növény, amely képes érzékelni és befolyásolni a környezetében lévő élőlények hangulatát. Virágai színe megváltozik az általa kibocsátott érzelmi energia függvényében. Gondozása különleges türelmet igényel.',
                'elohely' => 'Mexikói fennsík, mágikus botanikus kertek',
                'meret' => 'kicsi',
                'veszelyesseg' => 'ártalmatlan',
                'ritkasag' => 4,
                'felfedezes_datuma' => '2023-07-22',
                'felfedezo' => 'Prof. Maria Botanica',
                'allapot' => 'aktív',
                'kepessegek' => [20] // Hangulatbefolyásolás
            ],
            [
                'nev' => 'Digitális Sárkány',
                'tudomanyos_nev' => 'Draco digitalis',
                'leiras' => 'Egy virtuális térből a fizikai világba átlépett sárkány. Teste pixelekből áll, lélegzete bináris kódokat tartalmaz. Képes átjárni a digitális és fizikai világok között. Különösen vonzódik a számítógépekhez és az internethez.',
                'elohely' => 'Számítógép szerverek, data centerek, felhő infrastruktúra',
                'meret' => 'közepes',
                'veszelyesseg' => 'közepes',
                'ritkasag' => 9,
                'felfedezes_datuma' => '2023-11-08',
                'felfedezo' => 'Dr. Cyber Matrix',
                'allapot' => 'aktív',
                'kepessegek' => [6, 8] // Tűzvarázslat, Villamvarázslat
            ],
            [
                'nev' => 'Időugráló Nyúl',
                'tudomanyos_nev' => 'Lepus temporalis',
                'leiras' => 'Egy különösen gyors nyúl fajta, amely nem csak térben, hanem időben is képes ugrálni. Általában csak pár percet ugrik előre vagy hátra, de vannak példák hosszabb időutazásra is. Előszeretettel fogyasztja a temporális répát.',
                'elohely' => 'Időbuborékok, múlt és jövő határterületei',
                'meret' => 'kicsi',
                'veszelyesseg' => 'alacsony',
                'ritkasag' => 8,
                'felfedezes_datuma' => '2024-01-30',
                'felfedezo' => 'Dr. Időutazó Károly',
                'allapot' => 'aktív',
                'kepessegek' => [1, 16] // Szupergyorsaság, Időmanipuláció
            ],
            [
                'nev' => 'Láthatatlan Macska',
                'tudomanyos_nev' => 'Felis invisibilis',
                'leiras' => 'Egy macska fajta, amely teljesen képes láthatatlanná válni. Csak a dorombolása és a talpak nyoma árulja el a jelenlétét. Különösen szereti a halvány napfényt és a rejtett helyeket. Gondozása kihívást jelent.',
                'elohely' => 'Lombtalan erdők, árnyékos udvarok, titokzatos házak',
                'meret' => 'kicsi',
                'veszelyesseg' => 'ártalmatlan',
                'ritkasag' => 6,
                'felfedezes_datuma' => '2023-09-14',
                'felfedezo' => 'Rejtély Annie professzor',
                'allapot' => 'aktív',
                'kepessegek' => [4] // Láthatatlanság
            ],
            [
                'nev' => 'Jóslás Polip',
                'tudomanyos_nev' => 'Octopus prophetic',
                'leiras' => 'Egy különleges polip faj, amely képes megjósolni a jövőt. Karjaival különböző jeleket mutat, amelyekből tapasztalt kutatók meg tudják fejteni a jövőbeli eseményeket. Különösen híres a sportfogadások előrejelzéséről.',
                'elohely' => 'Mélytengeri mágikus vizek, jósló akváriumok',
                'meret' => 'közepes',
                'veszelyesseg' => 'alacsony',
                'ritkasag' => 9,
                'felfedezes_datuma' => '2023-05-18',
                'felfedezo' => 'Prof. Oracle Neptúnusz',
                'allapot' => 'aktív',
                'kepessegek' => [13] // Jövőlátás
            ],
            [
                'nev' => 'Fázisváltó Párduc',
                'tudomanyos_nev' => 'Panthera dimensionalis',
                'leiras' => 'Egy rendkívüli párduc, amely képes más dimenziókba átlépni. Bundája csillagokkal tarkított és folyamatosan változik. Vadászat közben gyakran eltűnik az egyik dimenzióból és váratlanul megjelenik egy másikban.',
                'elohely' => 'Dimenzióközti határ területek, párhuzamos erdők',
                'meret' => 'nagy',
                'veszelyesseg' => 'magas',
                'ritkasag' => 10,
                'felfedezes_datuma' => '2024-02-11',
                'felfedezo' => 'Dr. Parallaxis',
                'allapot' => 'kivizsgálás_alatt',
                'kepessegek' => [17, 4] // Dimenzióváltás, Láthatatlanság
            ]
        ];

        foreach ($lenyek as $lenyData) {
            $kepessegek = $lenyData['kepessegek'];
            unset($lenyData['kepessegek']);
            
            $lenyData['user_id'] = $admin->id;
            $lenyData['kategoria_id'] = Kategoria::inRandomOrder()->first()->id;
            
            $leny = Leny::create($lenyData);
            
            // Képességek hozzárendelése
            foreach ($kepessegek as $kepessegId) {
                $leny->kepessegek()->attach($kepessegId, [
                    'szint' => rand(1, 8),
                    'megjegyzes' => 'Természetes képesség'
                ]);
            }
        }
    }
}
