<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wildside\Userstamps\Userstamps;

class PedidoEstado extends Model
{
    use HasFactory,Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedido_estado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hora',
        'pedido_id',
        'estado_id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the pedido that owns the PedidoEstado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id', 'id');
    }

    /**
     * Get the user that owns the PedidoEstado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadosPedido::class, 'estado_id', 'id');
    }
}
