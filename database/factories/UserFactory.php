<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'dateNaissance' => $this->faker->date,
            'role' => $this->faker->randomElement(['admin', 'user']),
            'email' => $this->faker->unique()->safeEmail,
            'motDePasse' => $this->generatePassword(),
            'verify_email' => $this->faker->boolean,
        ];
    }

    /**
     * Génère un mot de passe conforme aux contraintes spécifiées.
     */
    private function generatePassword(): string
    {
        do {
            $password = $this->faker->password(8, 20);
        } while (!$this->isValidPassword($password));

        return Hash::make($password);
    }

    /**
     * Vérifie si le mot de passe respecte les contraintes spécifiées.
     */
    private function isValidPassword(string $password): bool
    {
        return strlen($password) >= 8 &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[!@#$%^&*().,\/]/', $password);
    }

    /**
     * Indicate that the user's role is admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user's role is user.
     */
    public function regularUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
        ]);
    }
}

