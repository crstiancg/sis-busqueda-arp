<?php

namespace App\Http\Controllers;

use App\Models\RegistroBusqueda;
use Illuminate\Http\Request;

class RegistroBusquedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RegistroBusqueda::with('solicitud','user','registroVerificacion')->get();
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
    public function show(RegistroBusqueda $registroBusqueda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroBusqueda $registroBusqueda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistroBusqueda $registroBusqueda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroBusqueda $registroBusqueda)
    {
        //
    }
}
