<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try{
            foreach ($request->user()->tokens as $token) {
                $token->delete();
            }
            return response()->json((new JsonResponseHelper())->success([]), Response::HTTP_OK);
        }catch(Exception $error){
            return response()->json(new JsonResponseHelper([$error], 'logout_error'), Response::HTTP_BAD_REQUEST);
        }
    }
}
