<?php

namespace App\Http\Controllers;

use App\Models\Arbolito;
use Illuminate\Http\Request;

class ArbolitoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Arbolito::take(10)->get();
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
    public function show(Arbolito $arbolito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arbolito $arbolito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arbolito $arbolito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arbolito $arbolito)
    {
        //
    }
}
