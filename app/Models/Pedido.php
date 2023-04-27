<?php

namespace App\Models;

use App\Models\Detallepedido;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory, Userstamps;


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
        "codigo",
        "fecha",
        "total",
        "cliente_id",
    ];



    /**
     * Get the cliente that owns the Pedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id', 'id');
    }



    /**
     * Get all of the detalle for the Pedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalle(): HasMany
    {
        return $this->hasMany(Detallepedido::class, 'pedido_id', 'id');
    }



    /**
     * Get all of the estados for the Pedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estados(): HasMany
    {
        return $this->hasMany(PedidoEstado::class, 'pedido_id', 'id');
    }


    /**
     * Get the ultimoestado associated with the Pedido
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ultimoestado(): HasOne
    {
        return $this->hasOne(PedidoEstado::class, 'pedido_id', 'id')->orderBy("hora", "desc");
    }

}
