<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreatureRequest;
use App\Http\Requests\UpdateCreatureRequest;
use App\Models\Leny;
use App\Models\Kategoria;
use App\Models\Kepesseg;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Leny::with(['kategoria', 'user', 'kepessegek', 'galeriakepek'])
            ->orderBy('created_at', 'desc');

        // Szűrések
        if ($request->filled('kategoria_id')) {
            $query->where('kategoria_id', $request->kategoria_id);
        }

        if ($request->filled('meret')) {
            $query->where('meret', $request->meret);
        }

        if ($request->filled('veszelyesseg')) {
            $query->where('veszelyesseg', $request->veszelyesseg);
        }

        if ($request->filled('allapot')) {
            $query->where('allapot', $request->allapot);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nev', 'LIKE', "%{$search}%")
                  ->orWhere('tudomanyos_nev', 'LIKE', "%{$search}%")
                  ->orWhere('leiras', 'LIKE', "%{$search}%")
                  ->orWhere('elohely', 'LIKE', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $lenyek = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $lenyek,
            'filters' => [
                'kategoriak' => Kategoria::aktiv()->get(['id', 'nev', 'szin']),
                'meretek' => ['apró', 'kicsi', 'közepes', 'nagy', 'hatalmas', 'gigantikus'],
                'veszelyessegek' => ['ártalmatlan', 'alacsony', 'közepes', 'magas', 'extrém'],
                'allapotok' => ['aktív', 'inaktív', 'kivizsgálás_alatt', 'eltűnt']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreatureRequest $request): JsonResponse
    {
        \Log::info('Creature creation attempt', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'has_file' => $request->hasFile('kep')
        ]);

        $data = $request->validated();
        
        // Kép feltöltés kezelése
        if ($request->hasFile('kep')) {
            $file = $request->file('kep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('creatures', $filename, 'public');
            $data['kep_url'] = asset('storage/' . $path);
            \Log::info('File uploaded', ['path' => $path, 'url' => $data['kep_url']]);
        }
        
        $leny = new Leny($data);
        $leny->user_id = auth()->id();
        $leny->save();

        \Log::info('Creature created successfully', ['id' => $leny->id]);

        // Képességek hozzárendelése
        if ($request->filled('kepessegek')) {
            $kepessegek = [];
            foreach ($request->kepessegek as $kepessegId) {
                $kepessegek[$kepessegId] = ['szint' => 1]; // Alapértelmezett szint
            }
            $leny->kepessegek()->attach($kepessegek);
        }

        $leny->load(['kategoria', 'user', 'kepessegek', 'galeriakepek']);

        return response()->json([
            'success' => true,
            'message' => 'A lény sikeresen létrehozva!',
            'data' => $leny
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $leny = Leny::with([
            'kategoria',
            'user',
            'kepessegek' => function($query) {
                $query->withPivot('szint', 'megjegyzes');
            },
            'galeriakepek' => function($query) {
                $query->rendezett();
            },
            'esemenyek' => function($query) {
                $query->publikus()->kronologikus()->limit(10);
            }
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $leny
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreatureRequest $request, string $id): JsonResponse
    {
        $leny = Leny::findOrFail($id);

        // Ellenőrizzük, hogy a felhasználó módosíthatja-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a lény módosításához.'
            ], 403);
        }

        $data = $request->validated();
        
        // Kép feltöltés kezelése
        if ($request->hasFile('kep')) {
            $file = $request->file('kep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('creatures', $filename, 'public');
            $data['kep_url'] = asset('storage/' . $path);
        }

        $leny->update($data);

        // Képességek frissítése
        if ($request->filled('kepessegek')) {
            $kepessegek = [];
            foreach ($request->kepessegek as $kepessegId) {
                $kepessegek[$kepessegId] = ['szint' => 1];
            }
            $leny->kepessegek()->sync($kepessegek);
        }

        $leny->load(['kategoria', 'user', 'kepessegek', 'galeriakepek']);

        return response()->json([
            'success' => true,
            'message' => 'A lény sikeresen frissítve!',
            'data' => $leny
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $leny = Leny::findOrFail($id);

        // Ellenőrizzük, hogy a felhasználó törölheti-e
        if ($leny->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultság a lény törléséhez.'
            ], 403);
        }

        $leny->delete();

        return response()->json([
            'success' => true,
            'message' => 'A lény sikeresen törölve!'
        ]);
    }

    /**
     * Kategóriák listázása
     */
    public function categories(): JsonResponse
    {
        $kategoriak = Kategoria::aktiv()->get();

        return response()->json([
            'success' => true,
            'data' => $kategoriak
        ]);
    }
}
