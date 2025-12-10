<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CreatureController;
use App\Http\Controllers\API\AbilityController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Nyilvános útvonalak
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact', [ContactController::class, 'store']); // Nyilvános kapcsolat űrlap

// Lények nyilvános listázása és megtekintése
Route::get('/creatures', [CreatureController::class, 'index']);
Route::get('/creatures/{id}', [CreatureController::class, 'show']);
Route::get('/categories', [CreatureController::class, 'categories']);

// Képességek nyilvános listázása
Route::get('/abilities', [AbilityController::class, 'index']);

// Lény galéria nyilvános megtekintése
Route::get('/creatures/{creatureId}/gallery', [GalleryController::class, 'index']);

// Authentikált útvonalak
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth útvonalak
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Lények CRUD műveletek
    Route::post('/creatures', [CreatureController::class, 'store']);
    Route::put('/creatures/{id}', [CreatureController::class, 'update']);
    Route::delete('/creatures/{id}', [CreatureController::class, 'destroy']);
    
    // Képességek hozzárendelése lényekhez
    Route::post('/creatures/{creatureId}/abilities', [AbilityController::class, 'attachToCreature']);
    Route::delete('/creatures/{creatureId}/abilities/{abilityId}', [AbilityController::class, 'detachFromCreature']);
    Route::put('/creatures/{creatureId}/abilities/{abilityId}', [AbilityController::class, 'updateCreatureAbility']);
    
    // Galéria kezelés
    Route::post('/creatures/{creatureId}/gallery', [GalleryController::class, 'store']);
    Route::put('/creatures/{creatureId}/gallery/{imageId}', [GalleryController::class, 'update']);
    Route::delete('/creatures/{creatureId}/gallery/{imageId}', [GalleryController::class, 'destroy']);
    Route::put('/creatures/{creatureId}/gallery/order', [GalleryController::class, 'updateOrder']);
    
    // Kapcsolati üzenetek kezelése (admin)
    Route::get('/contact', [ContactController::class, 'index']);
    Route::get('/contact/{id}', [ContactController::class, 'show']);
    Route::put('/contact/{id}/status', [ContactController::class, 'updateStatus']);
    Route::get('/contact/stats', [ContactController::class, 'stats']);
    Route::get('/creatures/{creatureId}/messages', [ContactController::class, 'creatureMessages']);
    
});
