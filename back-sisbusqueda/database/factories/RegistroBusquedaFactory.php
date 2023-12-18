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
            'estado' => $this->i%2===0 ? 1 : 0,
            'cod_protocolo' => $this->i%2===0 ? 'P-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT):null,
            'cod_escritura' => $this->i%2===0 ?'E-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT):null,
            'cod_folioInicial' => $this->i%2===0 ?'FI-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT):null,
            'cod_folioFinal' => $this->i%2===0 ?'FF-'.str_pad(++$this->v, 4, '0', STR_PAD_LEFT):null,
            'observaciones' => $this->i%2===0 ?fake()->text():null,
        ];
    }
}
