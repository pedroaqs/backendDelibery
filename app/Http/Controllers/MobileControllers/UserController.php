<?php

namespace App\Http\Controllers\MobileControllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\RegisterUserRequest;
use App\Http\Resources\Auth\UserLoginResource;
use App\Models\Persona;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function registerUser(RegisterUserRequest $request) {

        try {

            $usuario = User::firstOrNew(
                [
                    'name' => $request->name
                ]
            );
            $usuario->email = $request->name .'@deliverapp.com';
            $usuario->password = Hash::make($request->password);
            $usuario->save();

            return response()->json(new JsonResponseHelper(new UserLoginResource($usuario)), Response::HTTP_OK);

        } catch (Exception $e) {

            return response()->json(['error' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);

        }

    }

    public function registerPersonalData($request) {

        try {

            $persona = Persona::firstOrNew(
                [
                    'correo' => $request->correo
                ]
            );

            $persona->paterno = $request->paterno;
            $persona->materno = $request->materno;
            $persona->nombre = $request->nombre;
            $persona->correo = $request->correo;
            $persona->telefono = $request->telefono;
            $persona->activo = true;
            $persona->save();

            $user = Auth::user();
            $user->persona_id = $persona->id;
            $user->save();

            return response()->json(['message' => 'Datos personas registrados'],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function validateUserwithFacebook() {

    }
    public function validateUserwithGoogle() {

    }

}
