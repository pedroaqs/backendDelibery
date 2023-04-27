<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Auth\Role;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Auth\UsuarioResource;
use App\Http\Resources\MotorizadosResource;
use App\Models\Persona;

class UsuarioController extends Controller
{

    const ITEM_PER_PAGE = 5;
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

        $usuarios = User::query();

        if ($request->has("keybuscar") && $request->keybuscar != null) {
            $usuarios = $usuarios->where("name", "like", "%".$request->keybuscar."%")
                ->orWhere("dni", $request->keybuscar);
        }

        if ($request->has("idrol") && $request->idrol != null) {
            $usuarios = $usuarios->whereHas("roles", function ($query) use ($request) {
                $query->where("id", $request->idrol );
            });
        }


        $usuarios = $usuarios->orderBy("name", "asc")
            ->paginate($limit, ['*'], '', $page);

        return UsuarioResource::collection($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Guardando datos de persona
        $persona = Persona::firstOrNew(
            [
                'correo' => $request->correo
            ]
        );
        $persona->nro_documento = $request->nro_documento;
        $persona->paterno = $request->paterno;
        $persona->materno = $request->materno;
        $persona->nombre = $request->nombre;
        $persona->correo = $request->correo;
        $persona->telefono = $request->telefono;
        $persona->activo = true;
        $persona->save();

        // Guardando datos del usuario
        $usuario = User::firstOrNew(
            [
                'name' => $request->name
            ]
        );
        $usuario->persona_id = $persona->id;
        $usuario->email = $request->correo;
        $usuario->password = Hash::make($request->clave);
        $usuario->save();

        //Quitamos roles anteriores
        $roles = Role::where('guard_name', 'api')->get();
        foreach ($roles as $rol) {
            $rol_to_remove = Role::findById($rol->id, 'api');
            $usuario->removeRole($rol_to_remove);
        }

        //Asignamos rol apoderado
        $rol_to_add = Role::findById($request->rol_id, 'api');
        if ($rol_to_add != null) {
            $usuario->assignRole($rol_to_add);
        }

        return response()->json(["success" => "Datos actualizados correctamente"], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($usuario)
    {
        $usuario = User::where("id", $usuario)->with("persona")->first();

        if ($usuario != null) {
            return new UsuarioResource($usuario);
        }
        else {
            return response()->json(['error' => "El registro no ha sido encontrado"], 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipodocumento  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario)
    {
        // Guardando datos de persona
        $persona = Persona::firstOrNew(
            [
                'correo' => $request->correo
            ]
        );
        $persona->nro_documento = $request->nro_documento;
        $persona->paterno = $request->paterno;
        $persona->materno = $request->materno;
        $persona->nombre = $request->nombre;
        $persona->correo = $request->correo;
        $persona->telefono = $request->telefono;
        $persona->activo = true;
        $persona->save();

        $usuario = User::where("id", $usuario)->first();

        if ($usuario != null) {
            $usuario->email = $request->email;

            //Preguntamos si es esta cambiando la clave

            if( $request->has("clave")) {
                $usuario->password = Hash::make($request->clave);
            }

            $usuario->persona_id = $persona->id;
            $usuario->save();


            //Quitamos roles anteriores
            $roles = Role::where('guard_name', 'api')->get();
            foreach ($roles as $rol) {
                $rol_to_remove = Role::findById($rol->id, 'api');
                $usuario->removeRole($rol_to_remove);
            }

            //Asignamos rol apoderado
            $rol_to_add = Role::findById($request->rol_id, 'api');
            if ($rol_to_add != null) {
                $usuario->assignRole($rol_to_add);
            }

            return response()->json(["success" => "Datos actualizados correctamente"], 200);
        }
        else {
            return response()->json(['error' => "El registro no ha sido encontrado"], 403);
        }
    }



    public function actualizarmiclave(Request $request)
    {
        $user = Auth::user();

        if ($user != null) {
            $user->password = Hash::make($request->clave);
            $user->save();
            return response()->json(["data" => [
                    "success" => ["La clave ha sido actualizada correctamente"]
                ]],
                Response::HTTP_OK
            );
        } else {
            return response()->json(["data" => [
                    "error" => ["No se ha encontrado datos del usuario"]
                ]],
                Response::HTTP_NOT_FOUND
            );
        }
    }


    public function cambiarclaveapoderados()
    {
        $usuarios = User::whereHas("roles", function ($query){
            $query->where("id", 2 );
        })
        ->with(["persona"])
        ->get();

        foreach($usuarios as $usuario) {
            if($usuario->persona != null) {
                if($usuario->dni == $usuario->persona->doc_identidad) {
                    if($usuario->persona->paterno != null && $usuario->persona->materno != null) {
                        $paterno = $usuario->persona->paterno;
                        $materno = $usuario->persona->materno;
                        $clave = $usuario->dni.strtoupper($paterno[0]).strtoupper($materno[0]);
                        Log::alert('Clave generada: ' . $clave);
                        // Log::alert($usuario);
                        $usuario->password = Hash::make($clave);
                        $usuario->save();
                    } else {
                        // Log::alert('Apellidos no validos');
                        // Log::alert($usuario);
                    }
                    // Log::alert($usuario);
                } else {
                    // Log::alert('Dni diferente');
                    // Log::alert($usuario);
                }
            } else {
                // Log::alert('Persona no existe');
                // Log::alert($usuario);
            }
        }

        return response()->json(['message' => 'Claves generadas'], 200);

    }

    // Lista de usuarios con rol motorizado
    public function motorizados() {

        $users = User::role('motorizado','api')
        // $users = User::role('desarrollo','api')
            ->with([
                'persona',
                'ultimopedido.estadoactual'
            ])
            ->get();
        return MotorizadosResource::collection($users);
    }


}
