<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wildside\Userstamps\Userstamps;

class Pedidos extends Model
{
    use HasFactory,Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedidos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'fecha',
        'total',
        'cliente_id',
        'repartidor_id',
        'tienda_id',
        'cliente_sin_registro',
        'telefono_cliente_sin_registro'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get all of the estados for the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estados(): HasMany
    {
        return $this->hasMany(PedidoEstado::class, 'pedido_id', 'id');
    }

    /**
     * Get the user associated with the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estadoactual(): HasOne
    {
        return $this->hasOne(PedidoEstado::class, 'pedido_id', 'id')->latest();
    }

    /**
     * Get the cliente that owns the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id', 'id');
    }

    /**
     * Get the repartidor that owns the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repartidor_id', 'id');
    }

    /**
     * Get the tienda that owns the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tienda(): BelongsTo
    {
        return $this->belongsTo(Tienda::class, 'tienda_id', 'id');
    }

    /**
     * Get all of the detallepedido for the Pedidos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallepedido(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id', 'id');
    }
}
