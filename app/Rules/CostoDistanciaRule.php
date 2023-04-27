<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CostoDistanciaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $temp = null;
        $errores = [];
        foreach ($value as $costo_distancia) {
            # code...
            $costo_distancia = json_decode(json_encode($costo_distancia));
            if ($temp == null) {
                if($costo_distancia->distancia_inicial !== 0) {
                    array_push($errores,'item 1 -> valor 0');
                }
                if($costo_distancia->distancia_final <= $costo_distancia->distancia_inicial) {
                    array_push($errores,'item 1 -> valor final');
                }
                // if ($costo_distancia->costo > 0) {
                //     array_push($errores,'item');
                // }
                $temp = $costo_distancia;
            } else {
                if ($costo_distancia->distancia_inicial !== $temp->distancia_final + 1) {
                    array_push($errores,'item '.$costo_distancia->distancia_inicial);
                }
                if($costo_distancia->distancia_final <= $costo_distancia->distancia_inicial) {
                    array_push($errores,'item '.$costo_distancia->distancia_final);
                }
                // if ($costo_distancia->costo > 0) {
                //     array_push($errores,'item');
                // }
                $temp = $costo_distancia;
            }
        }
        return count($errores) == 0 ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Definicion de rangos de distancia incorrectos';
    }
}
