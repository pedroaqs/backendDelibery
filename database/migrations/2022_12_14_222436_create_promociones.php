<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromociones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_inicial');
            $table->timestamp('fecha_final');
            $table->foreignId('tienda_id')->constrained('tiendas')->nullable(true);
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('porcentaje_descuento');
            $table->float('precio_promocion');
            $table->boolean('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promociones');
    }
}
