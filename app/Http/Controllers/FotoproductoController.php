<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FotoproductoController extends Controller
{
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $foto = Foto::find($id);
        $foto->delete();

        $response = [
            "state" => "success",
            "message" => "Foto eliminada",
            "data" => []
        ];

        return response()->json($response, Response::HTTP_OK);
    }

}
