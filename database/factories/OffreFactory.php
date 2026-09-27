<?php

namespace Database\Factories;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offre>
 */
class OffreFactory extends Factory
{
    protected $model = Offre::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'recruteur']),
            'titre' => fake()->jobTitle(),
            'entreprise' => fake()->company(),
            'lieu' => fake()->city(),
            'type_contrat' => fake()->randomElement(['CDI', 'CDD', 'Stage', 'Alternance', 'Freelance']),
            'description' => fake()->paragraph(),
            'competences_requises' => fake()->words(3, true),
            'salaire' => fake()->numberBetween(25000, 55000) . '€',
            'active' => true,
        ];
    }
}
