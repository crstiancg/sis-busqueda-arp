<?php

namespace App\Http\Controllers;

use App\Models\Anterior;
use App\Models\Anterior2;
use App\Models\Arbolito;
use App\Models\Nuevo;
use App\Models\Nuevo2;
use App\Models\Sia;
use App\Models\Sis2018;
use App\Models\Sis2018_2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->table === 'all')
            return $this->generateListAll($request->column);
        elseif ($request->table === 'anterior')
            return $this->generateListAnterior($request->column);
        elseif ($request->table === 'anterior2')
            return $this->generateListAnterior2($request->column);
        elseif ($request->table === 'sis2018')
            return $this->generateListSis2018($request->column);
        elseif ($request->table === 'sis2018_2')
            return $this->generateListSis2018_2($request->column);
        elseif ($request->table === 'nuevo')
            return $this->generateListNuevo($request->column);
        elseif ($request->table === 'nuevo2')
            return $this->generateListNuevo2($request->column);
        elseif ($request->table === 'sia')
            return $this->generateListSia($request->column);
        elseif ($request->table === 'arbolito')
            return $this->generateListArbolito($request->column);
    }

    private function generateListAll(String $column){
        $tempTable = Anterior::select(DB::raw($column))->distinct()
            ->unionAll(Anterior2::select(DB::raw($column))->distinct())
            ->unionAll(Sis2018::select(DB::raw($column))->distinct())
            ->unionAll(Sis2018_2::select(DB::raw($column))->distinct())
            ->unionAll(Nuevo::select(DB::raw($column))->distinct())
            ->unionAll(Nuevo2::select(DB::raw($column))->distinct());

        return $this->generateSelectList($tempTable,$column);
    }

    private function generateListAnterior(String $column){
        $tempTable = Anterior::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListAnterior2(String $column){
        $tempTable = Anterior2::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListSis2018(String $column){
        $tempTable = Sis2018::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }

    private function generateListSis2018_2(String $column){
        $tempTable = Sis2018_2::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListNuevo(String $column){
        $tempTable = Nuevo::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListNuevo2(String $column){
        $tempTable = Nuevo2::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListSia(String $column){
        $tempTable = Sia::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateListArbolito(String $column){
        $tempTable = Arbolito::select(DB::raw($column))->distinct();

        return $this->generateSelectList($tempTable,$column);
    }
    private function generateTableAll(Request $request)
    {
        // $tempTable = Anterior::query();

        $tempTable = Anterior::select("*")
        ->unionAll(Anterior2::select("*"))
        ->unionAll(Sis2018::select("*"))
        ->unionAll(Sis2018_2::select("*"))
        ->unionAll(Nuevo::select("*"))
        ->unionAll(Nuevo2::select("*"));

        return  $this->generateViewSetList(
            $request,
            $tempTable,
            [], //para el filtrado
            ['notario',],  //para la busqueda
            ['notario','lugar'] //para el odenamiento
        );
    }

}
