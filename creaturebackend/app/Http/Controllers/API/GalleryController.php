<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryImageRequest;
use App\Models\Leny;
use App\Models\Galeriakep;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Lény galériájának megjelenítése
     */
    public function index(string $creatureId): JsonResponse
    {
        $leny = Leny::findOrFail($creatureId);
        $kepek = $leny->galeriakepek()->rendezett()->get();

        return response()->json([
            'success' => true,
            'data' => $kepek
        ]);
    }

    /**
     * Kép feltöltése a galériába
     */
    public function store(StoreGalleryImageRequest $request, string $creatureId): JsonResponse
    {
        $leny = Leny::findOrFail($creatureId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság képek feltöltéséhez.'
            ], 403);
        }

        $image = $request->file('image');
        $originalName = $image->getClientOriginalName();
        $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        
        // Kép mentése
        $path = $image->storeAs('gallery/' . $creatureId, $fileName, 'public');

        // Ha ez az első kép, vagy főképnek jelölték, akkor legyen főkép
        $foKep = $request->boolean('fo_kep') || $leny->galeriakepek()->count() === 0;

        // Ha főkép, akkor a többi kép ne legyen főkép
        if ($foKep) {
            $leny->galeriakepek()->update(['fo_kep' => false]);
        }

        $galeriakep = new Galeriakep([
            'leny_id' => $creatureId,
            'eredeti_nev' => $originalName,
            'fajl_nev' => $fileName,
            'fajl_utvonal' => $path,
            'mime_tipus' => $image->getMimeType(),
            'fajl_meret' => $image->getSize(),
            'leiras' => $request->leiras,
            'sorrend' => $request->sorrend ?? $leny->galeriakepek()->count(),
            'fo_kep' => $foKep
        ]);

        $galeriakep->save();

        return response()->json([
            'success' => true,
            'message' => 'Kép sikeresen feltöltve!',
            'data' => $galeriakep
        ], 201);
    }

    /**
     * Kép adatainak frissítése
     */
    public function update(Request $request, string $creatureId, string $imageId): JsonResponse
    {
        $request->validate([
            'leiras' => 'nullable|string|max:1000',
            'fo_kep' => 'boolean',
            'sorrend' => 'nullable|integer|min:0'
        ]);

        $leny = Leny::findOrFail($creatureId);
        $galeriakep = $leny->galeriakepek()->findOrFail($imageId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a kép módosításához.'
            ], 403);
        }

        // Ha főképnek jelölték, akkor a többi kép ne legyen főkép
        if ($request->boolean('fo_kep')) {
            $leny->galeriakepek()->where('id', '!=', $imageId)->update(['fo_kep' => false]);
        }

        $galeriakep->update($request->only(['leiras', 'fo_kep', 'sorrend']));

        return response()->json([
            'success' => true,
            'message' => 'Kép adatai sikeresen frissítve!',
            'data' => $galeriakep
        ]);
    }

    /**
     * Kép törlése
     */
    public function destroy(string $creatureId, string $imageId): JsonResponse
    {
        $leny = Leny::findOrFail($creatureId);
        $galeriakep = $leny->galeriakepek()->findOrFail($imageId);

        // Ellenőrizzük, hogy a felhasználó törölheti-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a kép törléséhez.'
            ], 403);
        }

        // Kép fájl törlése a storage-ból
        if (Storage::disk('public')->exists($galeriakep->fajl_utvonal)) {
            Storage::disk('public')->delete($galeriakep->fajl_utvonal);
        }

        // Ha ez volt a főkép, akkor válasszunk új főképet
        $voltFoKep = $galeriakep->fo_kep;
        $galeriakep->delete();

        if ($voltFoKep) {
            $ujFoKep = $leny->galeriakepek()->first();
            if ($ujFoKep) {
                $ujFoKep->update(['fo_kep' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Kép sikeresen törölve!'
        ]);
    }

    /**
     * Képek sorrendjének frissítése
     */
    public function updateOrder(Request $request, string $creatureId): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:galeriakepek,id',
            'images.*.sorrend' => 'required|integer|min:0'
        ]);

        $leny = Leny::findOrFail($creatureId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a sorrend módosításához.'
            ], 403);
        }

        foreach ($request->images as $imageData) {
            $leny->galeriakepek()
                ->where('id', $imageData['id'])
                ->update(['sorrend' => $imageData['sorrend']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Képek sorrendje sikeresen frissítve!'
        ]);
    }
}
