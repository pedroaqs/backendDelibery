<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromocionEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'fecha_inicial' => 'required',
            'fecha_final' => 'required',
            'tipo_promocion' => 'required',
            'porcentaje_descuento' => 'required_if:tipo_promocion,1',
            'precio_promocion' => 'required_if:tipo_promocion,2',
        ];
    }
}
