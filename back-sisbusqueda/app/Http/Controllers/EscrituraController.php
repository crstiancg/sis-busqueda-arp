<?php

namespace App\Http\Controllers;

use App\Models\Escritura;
use Illuminate\Http\Request;

class EscrituraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = Escritura::with(
            'subSerie',
            'libro',
                'libro.notario',
            'favorecidos',
            'otorgantes')->get();
        return $datos;
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
