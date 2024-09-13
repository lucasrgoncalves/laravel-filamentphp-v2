<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameProduct = $this->faker->words(asText: true);

        return [
            'name' => $nameProduct,
            'description' => $this->faker->sentence,
            'slug' => Str::slug($nameProduct)
        ];
    }
}
