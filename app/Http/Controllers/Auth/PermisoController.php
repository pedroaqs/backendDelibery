<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Auth\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;

class PermisoController extends Controller
{
    
    const ITEM_PER_PAGE = 7;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $page = Arr::get($searchParams, 'page', 1);

        $permisos = Permission::query();

        if ( $request->has("keybuscar") && $request->keybuscar != null ) {
            $permisos = $permisos->where("name", "like", $request->keybuscar."%");
        }

        $permisos = $permisos->orderBy("name", "desc")
        ->paginate($limit, ['*'], '', $page);
        
        return GlobalResource::collection( $permisos );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::create($request->all());
        return response()->json(["success" => "Datos actualizados correctamente"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipodocumento  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Permission ::where("id", $id)->first();
        
        if ($role != null) {
            return new GlobalResource($role);
        } 
        else {
            return response()->json(['error' => "El registro no ha sido encontrado"], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipodocumento  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role)
    {
        $role = Permission::where("id", $role)->first();
        
        if ($role != null) {

            DB::beginTransaction();
            try {
                $role->fill($request->all());
                if ($role->save()) {
                    DB::commit();
                    return response()->json(["success" => "Datos actualizados correctamente"], 200);
                }
                else {
                    return response()->json(["error" => "Error al actualizar los datos"], 403);
                }

            } catch (Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } 
        else {
            return response()->json(['error' => "El registro no ha sido encontrado"], 403);
        }
    }
    
}
