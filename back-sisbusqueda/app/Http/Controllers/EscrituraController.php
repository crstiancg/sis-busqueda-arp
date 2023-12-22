<?php

namespace App\Http\Controllers;

use App\Models\Escritura;
use Illuminate\Http\Request;

class EscrituraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $datos = Escritura::with(
        //     'subSerie',
        //     'libro',
        //         'libro.notario',
        //     'favorecidos',
        //     'otorgantes')->get();
        // return $datos;
        return $this->generateViewSetList(
            $request,
            Escritura::query(),
            ['libro_id'],
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escritura $escritura)
    {
        //
    }
}
