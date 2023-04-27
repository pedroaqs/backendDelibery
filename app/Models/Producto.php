<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Producto extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tienda_id',
        'categoria_id',
        'nombre',
        'precio',
        'descripcion',
    ];


    /**
     * Get all of the fotos for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(Foto::class, 'producto_id', 'id');
    }


    /**
     * Get the foto associated with the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function foto(): HasOne
    {
        return $this->hasOne(Foto::class, 'producto_id', 'id')->orderBy("id", "asc");
    }



    /**
     * Get the categoria that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categorias::class, 'categoria_id', 'id');
    }



    /**
     * Get all of the detallepedido for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallepedido(): HasMany
    {
        return $this->hasMany(Detallepedido::class, 'producto_id', 'id');
    }

}
