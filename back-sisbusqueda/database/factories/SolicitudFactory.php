<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitud>
 */
class SolicitudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notario_id' => fake()->numberBetween(1,10),
            'subserie_id' => fake()->numberBetween(1,10),
            'solicitante_id' => fake()->numberBetween(1,100),
            'otorgantes' => fake()->name().' '.fake()->lastName(),
            'favorecidos' => fake()->name().' '.fake()->lastName(),
            'fecha' => fake()->date(),
            'bien' => fake()->colorName(),
            'mas_datos' => fake()->text(),
            'tipo_copia' => fake()->randomElement(array('Testimonio','Copia Certificada','Copia Simple')),
            'cantidad_copia' => fake()->numberBetween(1,3),
            'precio_id' => 1,
            'ubigeo_cod' => fake()->randomElement(array('210101','210102','210103','210104','210105','210106')),
            'estado' => fake()->randomElement(array('En Busquueda','En verificación','Aceptado','Imprimido','Finalizado')),
            'user_id' => 1,
        ];
    }
}
