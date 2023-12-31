<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Escritura>
 */
class EscrituraFactory extends Factory
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
            'bien' => fake()->colorName(),
            'subserie_id' => fake()->numberBetween(1,10),
            'fecha' => fake()->date(),
            'cod_escritura' => 'E-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'cod_folioInicial' => 'FI-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'cod_folioFinal' => 'FF-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'libro_id' => fake()->numberBetween(1,30),
            'observaciones' => fake()->text(),
        ];
    }
}
