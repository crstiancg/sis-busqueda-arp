<?php

namespace App\Http\Controllers;

use App\Models\RegistroBusqueda;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class RegistroBusquedaController extends Controller
{
    
    public function index(Request $request)
    {
        // return $this->generateViewSetList(
        //     $request,
        //     Solicitud::where('estado','En Busquueda'),
        //     [],
        //     ['id', 'nombre'],
        //     ['id', 'nombre']
        // );
        return $this->generateViewSetList(
            $request,
            RegistroBusqueda::with('solicitud'),
            [],
            ['id', 'nombre'],
            ['id', 'nombre']
        );

        // return Solicitud::where('estado','Finalizado')->get();

        // return RegistroBusqueda::with('solicitud','user','registroVerificacion')->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $solicitude = Solicitud::find($id);
        return response()->json($solicitude);
    }

  
    public function edit(RegistroBusqueda $registroBusqueda)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $solicitude = Solicitud::find($id);
        return response()->json([$solicitude]);
    }


    public function destroy(RegistroBusqueda $registroBusqueda)
    {
        //
    }
}
