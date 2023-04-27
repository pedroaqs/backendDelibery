<?php

namespace App\Http\Requests;

use App\Rules\CostoDistanciaRule;
use Illuminate\Foundation\Http\FormRequest;

class CostoDistanciaCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'costos' => 'required',
            'costos.data' => ['required',new CostoDistanciaRule],
            'costos.data.*.distancia_inicial' => 'required',
            'costos.data.*.distancia_final' => 'required',
            'costos.data.*.costo' => 'required'
        ];
    }
}
