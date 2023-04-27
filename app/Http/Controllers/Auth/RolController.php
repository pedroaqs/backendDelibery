<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Auth\Role;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Auth\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;

class RolController extends Controller
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
        
        $roles = Role::query();

        if ($request->has("keybuscar") && $request->keybuscar != null) {
            $roles = $roles->where("name", "like", "%" . $request->keybuscar . "%");
        }
        

        $roles = $roles->orderBy("name", "desc")
            ->paginate($limit, ['*'], '', $page);

        return GlobalResource::collection($roles);
    }


    public function listarTodas()
    {
        $roles = Role::where("guard_name", "api")->get();
        return GlobalResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Role::create($request->all());
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
        $role = Role::where("id", $id)->first();

        if ($role != null) {
            return new GlobalResource($role);
        } else {
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
        $role = Role::where("id", $role)->first();

        if ($role != null) {

            DB::beginTransaction();
            try {
                $role->fill($request->all());
                if ($role->save()) {
                    DB::commit();
                    return response()->json(["success" => "Datos actualizados correctamente"], 200);
                } else {
                    return response()->json(["error" => "Error al actualizar los datos"], 403);
                }
            } catch (Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } else {
            return response()->json(['error' => "El registro no ha sido encontrado"], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipodocumento  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = Role::findOrFail($role);
        $role->delete();

        return response()->json(["success" => "Datos actualizados correctamente"], 200);
    }


    public function permisos($idrol)
    {
        $role = Role::findOrFail($idrol);
        $permisos = Permission::orderBy("name")->get();

        return response()->json([
            'data' => [
                "permisosActuales" => GlobalResource::collection($role->permissions),
                "permisos" => GlobalResource::collection($permisos),
            ]
        ], 200);

    }
    
    
    public function updatePermisos(Request $request, $idrol)
    {
        $rol = Role::findOrFail($idrol);
        
        if ($request->has('permisos') ) 
        {
            //Quitando todos los permisos
            $rol->revokePermissionTo( 
                Permission::whereIn('guard_name', ['api'])->get()->pluck('name')->toArray() 
            );
            
            //Asignando nuevos permisos
            $permisosNuevos = Permission::whereIn('id', $request->permisos)->get()->pluck('name')->toArray();
            $rol->givePermissionTo($permisosNuevos);
            
        }

        return response()->json(['data' => "Datos actualizados"], 200);

    }
    
}
