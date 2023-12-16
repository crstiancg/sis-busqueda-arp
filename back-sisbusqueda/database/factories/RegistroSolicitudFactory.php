<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroSolicitud>
 */
class RegistroSolicitudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $v = 0;
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'solicitud_id' => ++$this->v,
            'estado' => fake()->boolean(),
            'observaciones' => fake()->text(),
        ];
    }
}
