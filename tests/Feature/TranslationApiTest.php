<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Translation;
use App\Models\User;

class TranslationApiTest extends TestCase
{
    public function test_get_translation()
    {
	    // Create a user and issue an API token using Passport
	    $user = User::factory()->create();
	    $token = $user->createToken('TestToken')->accessToken;

	    // Create a translation
	    $translation = Translation::factory()->create();

	    // Make a GET request with the API token
	    $response = $this->getJson("/translations/{$translation->id}", [
	        'Authorization' => "Bearer {$token}",
	    ]);
	    $response->dump();  // Add this to see the response data for debugging
	    $response->assertStatus(200);
    }
    // Performance Test
    public function test_json_export_performance()
	{
	    $start = microtime(true);
	    $this->getJson('/translations/export');
	    $this->assertLessThan(500, (microtime(true) - $start) * 1000); // Response time < 500ms
	}
}