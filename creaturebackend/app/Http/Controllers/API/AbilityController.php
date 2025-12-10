<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Leny;
use App\Models\Kepesseg;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbilityController extends Controller
{
    /**
     * Képességek listázása
     */
    public function index(Request $request): JsonResponse
    {
        $query = Kepesseg::aktiv();

        if ($request->filled('tipus')) {
            $query->tipus($request->tipus);
        }

        $kepessegek = $query->orderBy('nev')->get();

        return response()->json([
            'success' => true,
            'data' => $kepessegek,
            'filters' => [
                'tipusok' => ['fizikai', 'mágikus', 'mentális', 'speciális']
            ]
        ]);
    }

    /**
     * Képesség hozzáadása lényhez
     */
    public function attachToCreature(Request $request, string $creatureId): JsonResponse
    {
        $request->validate([
            'kepesseg_id' => 'required|exists:kepessegek,id',
            'szint' => 'required|integer|between:1,10',
            'megjegyzes' => 'nullable|string|max:1000'
        ]);

        $leny = Leny::findOrFail($creatureId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a lény módosításához.'
            ], 403);
        }

        // Ellenőrizzük, hogy már van-e ilyen képessége
        if ($leny->kepessegek()->where('kepesseg_id', $request->kepesseg_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ez a képesség már hozzá van rendelve ehhez a lényhez.'
            ], 422);
        }

        $leny->kepessegek()->attach($request->kepesseg_id, [
            'szint' => $request->szint,
            'megjegyzes' => $request->megjegyzes
        ]);

        $kepesseg = Kepesseg::find($request->kepesseg_id);

        return response()->json([
            'success' => true,
            'message' => 'Képesség sikeresen hozzáadva!',
            'data' => [
                'kepesseg' => $kepesseg,
                'szint' => $request->szint,
                'megjegyzes' => $request->megjegyzes
            ]
        ]);
    }

    /**
     * Képesség eltávolítása lénytől
     */
    public function detachFromCreature(string $creatureId, string $abilityId): JsonResponse
    {
        $leny = Leny::findOrFail($creatureId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a lény módosításához.'
            ], 403);
        }

        $detached = $leny->kepessegek()->detach($abilityId);

        if ($detached) {
            return response()->json([
                'success' => true,
                'message' => 'Képesség sikeresen eltávolítva!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'A képesség nem volt hozzárendelve ehhez a lényhez.'
            ], 404);
        }
    }

    /**
     * Lény képességeinek frissítése
     */
    public function updateCreatureAbility(Request $request, string $creatureId, string $abilityId): JsonResponse
    {
        $request->validate([
            'szint' => 'required|integer|between:1,10',
            'megjegyzes' => 'nullable|string|max:1000'
        ]);

        $leny = Leny::findOrFail($creatureId);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a lény módosításához.'
            ], 403);
        }

        $updated = $leny->kepessegek()->updateExistingPivot($abilityId, [
            'szint' => $request->szint,
            'megjegyzes' => $request->megjegyzes
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Képesség szint sikeresen frissítve!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'A képesség nem volt hozzárendelve ehhez a lényhez.'
            ], 404);
        }
    }
}
