<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Librairie;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->word,
            'format' => $this->faker->randomElement(['jpg', 'png', 'jpeg']),
            'poids' => $this->faker->numberBetween(1, 9999999),
            'chemin' => $this->faker->url,
            'idLibrairie' => LibrairieFactory::factory(),
        ];
    }
}
