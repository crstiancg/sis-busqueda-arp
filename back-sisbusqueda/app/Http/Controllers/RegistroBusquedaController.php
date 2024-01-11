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

        // $queryRegistro = RegistroBusqueda::select('registro_busquedas.*')
        //     ->leftJoin('solicituds', 'registro_busquedas.solicitud_id', '=', 'solicituds.id')->where('solicituds.estado','Buscando');

        return $this->generateViewSetList(
            $request,
            RegistroBusqueda::query(),
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
        return response(RegistroBusqueda::create([
            'solicitud_id' => $request->solicitud_id,
            'user_id' => auth()->user()->id,
            'estado' => 0,
            'cod_protocolo' =>  $request->protocolo,
            'cod_escritura' => $request->cod_escritura,
            'cod_folioInicial' => $request->cod_folioInicial,
            'cod_folioFinal' => $request->cod_folioFinal,
            'observaciones' => $request->observaciones,
        ]), 201);
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
