<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $v = 10;
    public function definition(): array
    {
        return [
            'nombre' => fake()->name().' '.fake()->lastName(),
            'protocolo' => 'P-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'notario_id' => fake()->numberBetween(1,10),
        ];
    }
}
