<?php

namespace Database\Factories;

use App\Models\PasswordResetToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordResetTokenFactory extends Factory
{
    protected $model = PasswordResetToken::class;

    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail, 
            'token' => \Illuminate\Support\Str::random(64), 
        ];
    }
}
