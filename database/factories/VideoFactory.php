<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\Librairie;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->word,
            'format' => 'mp4',
            'poids' => $this->faker->numberBetween(10000001, 50000000),
            'chemin' => $this->faker->url,
            'idLibrairie' => LibrairieFactory::factory(),
        ];
    }
}
