<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notario>
 */
class NotarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => fake()->name(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'nombre_completo' => fake()->name().' '.fake()->lastName(),
            'año_inicio' => fake()->year(),
            'año_final' => fake()->year(),
            'ubigeo_cod' => fake()->randomElement(array('010101','010102','010103','010104','010105','010106')),
        ];
    }
}
