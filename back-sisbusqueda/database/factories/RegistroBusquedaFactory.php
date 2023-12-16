<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroBusqueda>
 */
class RegistroBusquedaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $i=0;
    private $v=0;
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'RS_id_derivado' => ++$this->i,
            'estado' => fake()->boolean(),
            'cod_protocolo' => 'P-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'cod_escritura' => 'E-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'cod_folioInicial' => 'FI-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'cod_folioFinal' => 'FF-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT),
            'observaciones' => fake()->text(),
        ];
    }
}
