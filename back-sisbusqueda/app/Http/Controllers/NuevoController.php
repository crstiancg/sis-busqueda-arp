<?php

namespace App\Http\Controllers;

use App\Models\Nuevo;
use Illuminate\Http\Request;

class NuevoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Nuevo::take(10)->get();
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
    public function show(Nuevo $nuevo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nuevo $nuevo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nuevo $nuevo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nuevo $nuevo)
    {
        //
    }
}
