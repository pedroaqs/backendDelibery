<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePedidoRequest extends FormRequest
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
            'tipo' => 'required',
            'cliente_sin_registro' => Rule::requiredIf(function() {
                return $this->tipo == 'ADMIN';
            }),
            'telefono_cliente_sin_registro' => Rule::requiredIf(function() {
                return $this->tipo == 'ADMIN';
            }),
            'tienda_id' => 'required',
            'detalle_pedido' => 'required|array',
        ];
    }
}
