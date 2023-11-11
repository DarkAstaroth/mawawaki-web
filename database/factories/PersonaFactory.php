<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Persona::class;


    public function definition(): array
    {
        return [
            'ci' => $this->faker->numerify('########'),
            'nombre' => $this->faker->firstName,
            'paterno' => $this->faker->lastName,
            'materno' => $this->faker->lastName,
            'fecha_nacimiento' => $this->faker->date,
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
            'telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
