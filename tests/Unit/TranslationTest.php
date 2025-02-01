<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Translation;
use App\Models\User;

class TranslationTest extends TestCase
{
    public function test_create_translation()
    {
        $translation = Translation::factory()->create();
        $this->assertModelExists($translation);
    }



}