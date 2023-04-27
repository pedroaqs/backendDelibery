<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Promocion extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promociones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'tienda_id',
        'producto_id',
        'porcentaje_descuento',
        'precio_promocion',
        'activo'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;




    /**
     * Get the tienda that owns the Promocion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tienda(): BelongsTo
    {
        return $this->belongsTo(Tienda::class, 'tienda_id', 'id');
    }


    /**
     * Get the producto that owns the Promocion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
