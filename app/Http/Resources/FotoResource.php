<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class FotoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "ruta" => $this->ruta,
            "name" => $this->ruta,
            "producto_id" => $this->producto_id,
            "archivo_existe" => $this->getFileExiste(),
            "url" => $this->getLink(),
            "asset" => $this->asset(),
        ];

    }



    /**
     * Devuelve true/false
     */
    private function getFileExiste()
    {
        if (is_file( storage_path().'/app/'.$this->valegeneradoArchivo  )) {
            return true;
        }
        else {
            return false;
        }
    }



    /**
     * Obtener link del archivo
     */
    private function getLink() {
        return route("home")."/descargararchivo?rutaarchivo=".$this->ruta;
    }

    public function asset() {
        if ($this->ruta)
            if(file_exists(storage_path('app/' . $this->ruta))) return base64_encode(file_get_contents(storage_path('app/'.$this->ruta)));
            // if(file_exists(storage_path('app/' . $this->ruta))) return route("home")."/descargararchivo?rutaarchivo=".$this->ruta;
        // return asset('storage/no_image.jpeg');
        return base64_encode(file_get_contents(storage_path('app/public/no_image.jpeg')));

    }

}
