<?php

namespace Database\Factories;

use App\Models\Librairie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibrairieFactory extends Factory
{
    protected $model = Librairie::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word,
            'idUser' => UserFactory::factory(),
        ];
    }
}


