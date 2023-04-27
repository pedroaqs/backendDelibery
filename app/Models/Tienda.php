<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tienda extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tiendas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ruc',
        'razonsocial',
        'logo',
        'latitud',
        'longitud',
        'categoria_id',
        'calificacion_promedio'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the categoria that owns the Tienda
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categorias::class, 'categoria_id', 'id');
    }

    public function getLogo() {
        if ($this->logo)
            if(file_exists(storage_path('app/public/tiendas/' . $this->logo))) return asset('storage/tiendas/' . $this->logo);

        return asset('storage/no_image.jpeg');
    }
}
