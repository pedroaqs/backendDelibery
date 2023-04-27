<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class CostoDistancia extends Model
{
    use HasFactory,Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'costo_distancia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distancia_inicial',
        'distancia_final',
        'costo'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
