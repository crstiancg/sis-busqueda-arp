<?php

namespace App\Http\Controllers;

use App\Models\Anterior;
use App\Models\Anterior2;
use App\Models\Nuevo;
use App\Models\Sis2018;
use App\Models\Sis2018_2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnteriorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $tempTable = Anterior::query();

        $tempTable = Anterior::select(DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', notario, ' '), '[[:space:]]+', ' ')) as notario"))
        ->unionAll(Anterior2::select(DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', notario, ' '), '[[:space:]]+', ' ')) as notario")))
        ->unionAll(Nuevo::select(DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', notario, ' '), '[[:space:]]+', ' ')) as notario")));
    
    $subquery = $tempTable->toSql();
    
    return DB::table(DB::raw("({$subquery}) as TempTable"))
        ->distinct()
        ->whereNotNull('notario')
        ->orderBy('notario', 'asc')
        ->get();

        // return  $this->generateViewSetList(
        //     $request,
        //     $tempTable,
        //     [], //para el filtrado
        //     ['notario',],  //para la busqueda
        //     ['notario','lugar'] //para el odenamiento
        // );
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
    public function show(Anterior $anterior)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anterior $anterior)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anterior $anterior)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anterior $anterior)
    {
        //
    }
}
