<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $count = 0; // Keep track of iterations to generate unique keys
        
        return [
            // Use a static count to generate unique keys
            'key' => 'key_' . $count++, 
            // Generate a 'content' field with JSON encoding (adjust languages as needed)
            'content' => json_encode([
                'en' => $this->faker->sentence,
                'fr' => $this->faker->sentence,
                'de' => $this->faker->sentence,
                'es' => $this->faker->sentence,
            ]),
            // Optionally set a random tag
            'tag' => $this->faker->word(),
        ];
    }
}
