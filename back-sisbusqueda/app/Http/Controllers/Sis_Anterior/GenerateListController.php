<?php

namespace App\Http\Controllers\Sis_Anterior;

use App\Http\Controllers\Controller2;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sis_AnteriorModels\Anterior;
use App\Models\Sis_AnteriorModels\Anterior2;
use App\Models\Sis_AnteriorModels\Arbolito;
use App\Models\Sis_AnteriorModels\Nuevo;
use App\Models\Sis_AnteriorModels\Nuevo2;
use App\Models\Sis_AnteriorModels\Sia;
use App\Models\Sis_AnteriorModels\Sis2018;
use App\Models\Sis_AnteriorModels\Sis2018_2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateListController extends Controller2
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
    public function generateTableAll(Request $request)
    {
        // return $request;

        // $tempTable = Libro::with('escrituras');
        $tempTable = Anterior::select("*")
            ->unionAll(Anterior2::select("*"))
            ->unionAll(Sis2018::select("*"))
            ->unionAll(Sis2018_2::select("*"))
            ->unionAll(Nuevo::select("*"))
            ->unionAll(Nuevo2::select("*"));

        return $this->QueryGenerateViewSetList($request,$tempTable,[],[],[]);

        // return  $this->generateViewSetList(
        //     $request,
        //     $tempTable,
        //     ['notario','lugar','subserie','otorgantes'], //para el filtrado
        //     ['id','notario',],  //para la busqueda
        //     ['id','notario','lugar','subserie','fecha','bien','protocolo'] //para el odenamiento
        // );
    }

    public function generateSelectList(Builder $querySet,String $column){
        $querySetSql = $querySet->toSql();
        return DB::table(DB::raw("($querySetSql) as TempTable"))
            ->select(DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', ".$column.", ' '), '[[:space:]]+', ' ')) as ".$column))
            ->distinct()->whereNotNull($column)
            ->orderBy("$column",'asc')
            ->get();
    }

}
