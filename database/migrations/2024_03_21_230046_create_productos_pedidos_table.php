<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pedido_id')->index('productos_pedidos_pedido_id_foreign');
            $table->unsignedBigInteger('user_id')->index('productos_pedidos_user_id_foreign');
            $table->unsignedBigInteger('product_id')->index('productos_pedidos_product_id_foreign');
            $table->integer('cantidad_solicitada');
            $table->timestamps();
            $table->integer('cantidad_pendiente')->nullable();
            $table->double('monto', 8, 2)->nullable()->default(0);
            $table->double('descuento', 8, 2)->nullable()->default(0);
            $table->double('neto', 8, 2)->nullable()->default(0);
            $table->integer('cantidad_negada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_pedidos');
    }
};
