<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',9);
            $table->timestamp('fecha');
            $table->decimal('total')->nullable(true);
            $table->foreignId('cliente_id')->nullable(true)->constrained('users');
            $table->foreignId('repartidor_id')->nullable(true)->constrained('users');
            $table->foreignId('tienda_id')->constrained('tiendas');
            $table->string('cliente_sin_registro')->nullable(true);
            $table->string('telefono_cliente_sin_registro',15)->nullable(true);
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
