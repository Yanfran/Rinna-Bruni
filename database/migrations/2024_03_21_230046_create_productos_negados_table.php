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
        Schema::create('productos_negados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('productos_negados_product_id_foreign');
            $table->unsignedBigInteger('user_id')->index('productos_negados_user_id_foreign');
            $table->unsignedBigInteger('tienda_id')->index('productos_negados_tienda_id_foreign');
            $table->unsignedBigInteger('estatus')->default(0);
            $table->integer('cantidad');
            $table->timestamps();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->enum('origen', ['pedido', 'Sin inventario'])->default('pedido');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_negados');
    }
};
