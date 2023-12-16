<?php

namespace Database\Seeders;

use App\Models\Escritura;
use App\Models\Favorecido;
use App\Models\Libro;
use App\Models\Notario;
use App\Models\Otorgante;
use App\Models\RegistroBusqueda;
use App\Models\RegistroSolicitud;
use App\Models\RegistroVerificacion;
use App\Models\Solicitante;
use App\Models\Solicitud;
use App\Models\SubSerie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class A_de_factoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notario::factory(10)->create();
        Libro::factory(30)->create();
        SubSerie::factory(10)->create();
        Escritura::factory(30)->create();
        Favorecido::factory(60)->create();
        Otorgante::factory(60)->create();

        Solicitante::factory(100)->create();
        Solicitud::factory(50)->create();
        RegistroSolicitud::factory(50)->create();
        RegistroBusqueda::factory(50)->create();
        RegistroVerificacion::factory(50)->create();
    }
}
