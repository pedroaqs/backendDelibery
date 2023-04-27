<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DescargararchivoController extends Controller
{
    
    public function index ( Request $request ) 
    {
        $ruta_archivo = storage_path().'/app/'.$request->input("rutaarchivo") ;

        if ( file_exists( $ruta_archivo ) ) {
            return response()->download($ruta_archivo);
        } else {
            exit( 'No se ha encontrado el archivo solicitado... ');
        }
    }

}
