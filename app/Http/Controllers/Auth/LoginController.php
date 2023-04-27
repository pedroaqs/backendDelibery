<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserLoginResource;

class LoginController extends Controller
{
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('name', $request->username)
        ->with([
            "roles"
        ])
        ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $msg = [
                'title' => 'Atención',
                'message' => 'Los datos ingresados no son correctos'
            ];

            return response()->json(['error' => $msg], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new JsonResponseHelper(new UserLoginResource($user)), Response::HTTP_OK);
    }
    
}
