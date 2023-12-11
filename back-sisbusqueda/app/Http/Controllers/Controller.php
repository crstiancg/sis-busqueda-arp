<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getPageSize()
    {
        if (request()->filled('per_page')) {
            return intval(request()->input('per_page'));
        }
        if (request()->filled('page_size')) {
            return intval(request()->input('page_size'));
        }
        if (request()->filled('rowsPerPage')) {
            return intval(request()->input('rowsPerPage'));
        }
        return config('controller.page_size', 20);
    }

    public function generateViewSetList(Request $request, Builder $querySet, array $filterBy, array $searchBy, array $orderBy)
    {
        function addOrSkipBaseTable(string $colName, string $tableBaseName)
        {
            if (strpos($colName, '.') === false) {
                return $tableBaseName . '.' . $colName;
            }
            return $colName;
        }
        $tableBaseName = $querySet->getModel()->getTable();
        if ($request->hasAny($filterBy)) {
            foreach ($filterBy as $filter) {
                if ($request->filled($filter)) {
                    $querySet->where(
                            DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', ".addOrSkipBaseTable($filter, $tableBaseName).", ' '), '[[:space:]]+', ' '))"),
                            $request->input($filter));
                }
            }
        }
        if ($request->filled('search')) {
            $querySet->where(function ($q) use ($searchBy, $request, $tableBaseName) {
                foreach ($searchBy as $searchByCol) {
                    $q->orwhere(
                        DB::raw("TRIM(BOTH ' ' FROM REGEXP_REPLACE(CONCAT(' ', ".addOrSkipBaseTable($searchByCol, $tableBaseName).", ' '), '[[:space:]]+', ' '))"),
                        'like',
                        '%' . $request->input('search') . '%');
                }
                return $q;
            });
        }
        if ($request->filled('order_by')) {
            $searchOrderList = explode(',', $request->input('order_by'));
            foreach ($searchOrderList as $searchOrderParam) {
                $searchOrderParamWithoutSign = preg_replace('/-/', '', $searchOrderParam, 1);
                $orderDirection = substr($searchOrderParam, 0, 1) === '-' ? 'desc' : 'asc';
                if (in_array($searchOrderParamWithoutSign, $orderBy, true)) {
                    $querySet->orderBy($searchOrderParamWithoutSign, $orderDirection);
                }
            }
        }

        return $this->getPageSize()?$querySet->paginate($this->getPageSize()):response()->json(['data'=>$querySet->get()]);
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
