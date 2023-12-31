<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibroRequest;
use App\Models\Libro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibroController extends Controller2
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $libros = Libro::orderBy('updated_at', 'desc');
        return  $this->generateViewSetList(
            $request,
            $libros,
            $libros->getModel()->getFillable(), //para el filtrado
            $libros->getModel()->getFillable(),  //para la busqueda
            $libros->getModel()->getFillable() //para el odenamiento
        );
        // $libros = Libro::with('notario')->OrderByDesc('created_at')->limit(20)->get();
        // return response()->json($libros);
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
    public function store(StoreLibroRequest $request)
    {
        // return $request->all();
        // $request->get('update_at')->now();
        return response(Libro::create([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'protocolo' => $request->protocolo,
            'notario_id' => $request->notario_id,
            'updated_at' => Carbon::now()
        ]),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Libro $libro)
    {
        //
        return response()->json($libro);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLibroRequest $request, Libro $libro)
    {
        return response($libro->update([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'protocolo' => $request->protocolo,
            'notario_id' => $request->notario_id,
            'updated_at' => Carbon::now()
        ]),201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        //
    }
}
