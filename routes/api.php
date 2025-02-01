<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Http;

Route::post('/oauth/token', function (Request $request) {
	
    $client = Client::where('password_client', true)->first();

    if (!$client) {
        return response()->json(['error' => 'Password client not found'], 500);
    }

    $response = Http::asForm()->post(url('/oauth/token'), [
        'grant_type' => 'password',
        'client_id' => $client->id,
        'client_secret' => $client->secret,
        'username' => $request->username,
        'password' => $request->password,
        'scope' => '*',
    ]);

    return $response->json();
});
Route::middleware('auth:api')->group(function () {
    Route::post('/translations', [TranslationController::class, 'store']); // Create a new translation
    Route::put('/translations/{id}', [TranslationController::class, 'update']); // Update a translation
    Route::get('/translations/{id}', [TranslationController::class, 'show']); // Get a translation by ID
    Route::get('/translations', [TranslationController::class, 'index']); // List translations with filtering & pagination
    Route::get('/translations/export', [TranslationController::class, 'export']); // Export translations as JSON
    Route::get('/translations/search', [TranslationController::class, 'search']); // Search translations with pagination
});
