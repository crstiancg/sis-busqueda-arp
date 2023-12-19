<?php

namespace App\Http\Controllers;

use App\Models\Sis2018_2;
use Illuminate\Http\Request;

class Sis20182Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return  $this->generateViewSetList(
            $request,
            Sis2018_2::query(),
            ['notario','lugar','subserie'], //para el filtrado
            ['id','notario',],  //para la busqueda
            ['id','notario','lugar','subserie','fecha','bien','protocolo'] //para el odenamiento
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
    public function show(Sis2018_2 $sis2018_2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sis2018_2 $sis2018_2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sis2018_2 $sis2018_2)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sis2018_2 $sis2018_2)
    {
        //
    }
}
