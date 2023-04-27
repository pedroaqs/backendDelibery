<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriasResource;
use App\Models\Categorias;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Finder\Glob;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\GlobalResource;
use App\Http\Resources\Opciones\CategoriasComeciosResource;
use Illuminate\Support\Facades\Storage;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $request->validate(['tipo' => 'required']);

        $limit = Arr::get($request->all(), 'limit', 15);
        $keyword = Arr::get($request->all(), 'keyword', null);

        $listaCategorias = Categorias::where('tipo',$request->tipo);

        if($keyword != null) {
            $listaCategorias = $listaCategorias->where('nombre','like','%'.$keyword.'%');
        }

        return CategoriasResource::collection ($listaCategorias->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info($request);
        // @audit falta colocar permiso
        $request->validate([
            'nombre' => 'required',
            'tipo' => 'required'
        ]);

        $nombre = Arr::get($request->all(), 'nombre', null);
        $tipo = Arr::get($request->all(), 'tipo', null);

        $foto = null;
        if( $request->hasFile('file')) {
            $extension = $request->file->getClientOriginalExtension();
            Storage::putFileAs('public/categorias', $request->file('file'),$nombre.time().'.'.$extension);
            $foto = $nombre.time(). '.' .$extension;
        }

        Categorias::create([
            'nombre' => $nombre,
            'foto' => $foto,
            'tipo' => $tipo,
        ]);

        return response()->json(['message' => 'Categoria registrada.'],Response::HTTP_OK);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return new CategoriasResource(Categorias::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::info('request');
        Log::info($request);
        // @audit falta colocar permiso
        $request->validate([
            'nombre' => 'required',
            'id' => 'required',
        ]);
        $categoria = Categorias::find($request->id);
        if ($categoria == null) {
            return response()->json(['error' => 'No se encontro el registro a actualizar.'],Response::HTTP_BAD_REQUEST);
        }
        $nombre = Arr::get($request->all(), 'nombre', null);
        $tipo = Arr::get($request->all(), 'tipo', null);

        $foto = null;
        if( $request->hasFile('file')) {
            // @audit Falta eliminar la imagen previa
            if ($categoria->foto != null) {
                unlink(storage_path('app/public/categorias').'/'.$categoria->foto);
            }
            $extension = $request->file->getClientOriginalExtension();
            Storage::putFileAs('public/categorias', $request->file('file'),$nombre.time().'.'.$extension);
            $foto = $nombre.time().'.'.$extension;
        }
        $categoria->nombre = $nombre;
        $categoria->foto = $foto;
        $categoria->save();
        return response()->json(['message' => 'Registo actualizado.'],Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Metodos para lista de opciones
     */

    public function listaCategoriasComercios()
    {
        $categorias = Categorias::where('tipo','COMERCIO')->get();
        return CategoriasComeciosResource::collection($categorias);
    }
    public function listaCategoriasProductos()
    {
        $categorias = Categorias::where('tipo','PRODUCTO')->get();
        return CategoriasComeciosResource::collection($categorias);
    }
}
