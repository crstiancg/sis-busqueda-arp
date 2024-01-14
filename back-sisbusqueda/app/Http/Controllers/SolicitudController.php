<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\RegistroBusqueda;
use App\Models\Solicitante;
use App\Models\Solicitud;
use App\Models\Tupa;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $solicitudesConUbigeos = Solicitud::orderBy('updated_at', 'desc')->join('ubigeos', 'solicituds.ubigeo_cod', '=', 'ubigeos.codigo')
            ->select('solicituds.*', 'ubigeos.nombre as ubigeo_nombre')
            ->with('solicitante','solicitante.ubigeo','subserie','ubigeo','notario'); // Incluir relaciones adicionales si es necesario

        // return $solicitudesConUbigeos;//Solicitud::with('solicitante')->get();
        return $this->generateViewSetList(
            $request,
            Solicitud::orderBy('updated_at', 'desc')->with('solicitante','solicitante.ubigeo','subserie','ubigeo','notario','tupa'),
            ['area_id','estado','user_id'],
            ['id'],
            ['id'],
            // $solicitudesConUbigeos,
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // $existeSolicitante = Solicitante::where('num_documento',$request->num_documento)->first();
        // $id_solicitabte = null;
        // if($existeSolicitante){
        //     $id_solicitabte = $existeSolicitante->id;
        // }else{
            $solicitante = Solicitante::create([
                'nombres' => $request->nombres,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'nombre_completo' => $request->nombre_completo,//$request->nombres.' '.$request->apellido_paterno.' '.$request->apellido_materno,
                'tipo_documento' => $request->tipo_documento,
                'num_documento' => $request->num_documento,
                'direccion' => $request->direccion,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'ubigeo_cod' => $request->ubigeo_cod,
            ]);
        // }
        $uit = \env('PAGO');
        
        $id_solicitabte = $solicitante->id;
        $id_precio = Tupa::first();
        $solicitud = Solicitud::create([
            'notario_id' => $request->notario_id, 
            'subserie_id'=> $request->subserie_id,
            'solicitante_id'=> $id_solicitabte,
            'otorgantes'=> $request->otorgantes,
            'favorecidos'=> $request->favorecidos,
            'fecha'=> $request->fecha,
            'ubigeo_cod'=> $request->ubigeo_cod_soli,
            'bien'=> $request->bien,
            'mas_datos'=> $request->mas_datos,
            'pago_busqueda' => $request->precio,
            'area_id' => $request->area_id,
            'estado'=> 'Buscando',              // ojo con los estados
            'user_id' =>auth()->user()->id,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        // return response($this->RegistrarABusqueda($solicitud),201);

        return response([$request],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitud $solicitude)
    {
        return response()->json($solicitude);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitud $solicitude)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitud $solicitude)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitud $solicitude)
    {
        return response()->json($solicitude->delete());
    }

    private function RegistrarABusqueda(Solicitud $solicitude){
        return RegistroBusqueda::create([
            'solicitud_id' =>$solicitude->id,
            'estado' => 0,
        ]);
    }
}
