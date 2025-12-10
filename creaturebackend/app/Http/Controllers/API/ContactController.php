<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Models\KapcsolatiUzenet;
use App\Models\Leny;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Kapcsolati üzenet küldése
     */
    public function store(ContactMessageRequest $request): JsonResponse
    {
        $uzenet = new KapcsolatiUzenet($request->validated());
        $uzenet->ip_cim = $request->ip();
        $uzenet->user_agent = $request->header('User-Agent');
        $uzenet->save();

        return response()->json([
            'success' => true,
            'message' => 'Üzenet sikeresen elküldve! Hamarosan válaszolunk.',
            'data' => [
                'id' => $uzenet->id,
                'nev' => $uzenet->nev,
                'targy' => $uzenet->targy,
                'created_at' => $uzenet->created_at
            ]
        ], 201);
    }

    /**
     * Kapcsolati üzenetek listázása (admin)
     */
    public function index(Request $request): JsonResponse
    {
        // Ez csak authentikált felhasználóknak elérhető
        $query = KapcsolatiUzenet::with(['leny'])
            ->legujabb();

        // Szűrések
        if ($request->filled('allapot')) {
            $query->allapot($request->allapot);
        }

        if ($request->filled('tipus')) {
            $query->tipus($request->tipus);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nev', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('targy', 'LIKE', "%{$search}%")
                  ->orWhere('uzenet', 'LIKE', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $uzenetek = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $uzenetek,
            'filters' => [
                'allapotok' => ['új', 'feldolgozás_alatt', 'válaszolt', 'lezárt'],
                'tipusok' => ['általános', 'lény_bejelentés', 'hiba_jelentés', 'javaslat', 'egyéb']
            ]
        ]);
    }

    /**
     * Kapcsolati üzenet részletei
     */
    public function show(string $id): JsonResponse
    {
        $uzenet = KapcsolatiUzenet::with(['leny'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $uzenet
        ]);
    }

    /**
     * Üzenet állapotának frissítése
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'allapot' => 'required|in:új,feldolgozás_alatt,válaszolt,lezárt',
            'admin_megjegyzes' => 'nullable|string|max:2000'
        ]);

        $uzenet = KapcsolatiUzenet::findOrFail($id);
        $uzenet->update($request->only(['allapot', 'admin_megjegyzes']));

        return response()->json([
            'success' => true,
            'message' => 'Üzenet állapota sikeresen frissítve!',
            'data' => $uzenet
        ]);
    }

    /**
     * Kapcsolatfelvételi típusok és statisztikák
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'osszes' => KapcsolatiUzenet::count(),
            'uj' => KapcsolatiUzenet::uj()->count(),
            'feldolgozas_alatt' => KapcsolatiUzenet::allapot('feldolgozás_alatt')->count(),
            'valaszolt' => KapcsolatiUzenet::allapot('válaszolt')->count(),
            'lezart' => KapcsolatiUzenet::allapot('lezárt')->count(),
            'tipusok' => KapcsolatiUzenet::selectRaw('tipus, COUNT(*) as count')
                ->groupBy('tipus')
                ->get()
                ->pluck('count', 'tipus'),
            'legutolso_7_nap' => KapcsolatiUzenet::where('created_at', '>=', now()->subDays(7))->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Lényhez kapcsolódó üzenetek
     */
    public function creatureMessages(string $creatureId): JsonResponse
    {
        $leny = Leny::findOrFail($creatureId);
        $uzenetek = $leny->kapcsolatiUzenetek()
            ->legujabb()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $uzenetek,
            'leny' => [
                'id' => $leny->id,
                'nev' => $leny->nev
            ]
        ]);
    }
}
