<?php

namespace App\Http\Controllers;

use App\Models\RegistroVerificacion;
use Illuminate\Http\Request;

class RegistroVerificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->generateViewSetList(
            $request,
            RegistroVerificacion::query(),
            [],
            ['id', 'nombre'],
            ['id', 'nombre']
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
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistroVerificacion $registroVerificacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroVerificacion $registroVerificacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistroVerificacion $registroVerificacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroVerificacion $registroVerificacion)
    {
        //
    }
}
