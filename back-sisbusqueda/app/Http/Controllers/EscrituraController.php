<?php

namespace App\Http\Controllers;

use App\Models\Escritura;
use Illuminate\Http\Request;

class EscrituraController extends Controller2
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $datos = Escritura::with(
            'subSerie',
            'favorecidos',
            'otorgantes');
        // return $datos;

        return $this->generateViewSetList(
            $request,
            $datos,
            $datos->getModel()->getFillable(),
            ['bien'],
            ['id', 'bien']
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
        //
        $escritura = Escritura::create($request->all());
        if ($request->has('otorgantes')) {
            $escritura->otorgantes()->attach($request->input('otorgantes'));
        }
        if ($request->has('favorecidos')) {
            $escritura->favorecidos()->attach($request->input('favorecidos'));
        }
        return response()->json($escritura, 201);
        // return response(, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Escritura $escritura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Escritura $escritura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Escritura $escritura)
    {
        $escritura->update($request->all());
        $escritura_otorgante = [];
        foreach ($request->otorgantes as $otorganteid) {
            $escritura_otorgante[] = [
                'escritura_id' => $escritura->id,
                'otorgante_id' => $otorganteid,
            ];
        }
        $escritura->otorgantes()->sync($escritura_otorgante);
        $escritura_favorecido = [];
        foreach ($request->favorecidos as $favorecidoid) {
            $escritura_favorecido[] = [
                'escritura_id' => $escritura->id,
                'favorecido_id' => $favorecidoid,
            ];
        }
        $escritura->favorecidos()->sync($escritura_favorecido);
        return response()->json($escritura, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escritura $escritura)
    {
        //
    }
}
