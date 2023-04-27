<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadosPedido extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estados_pedido';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    /**
     * Get all of the pedidoestado for the EstadosPedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidoestado(): HasMany
    {
        return $this->hasMany(PedidoEstado::class, 'estado_id', 'id');
    }
}
