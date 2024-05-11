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
        Schema::create('productos_gestion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('productos_gestion_product_id_foreign');
            $table->unsignedBigInteger('user_id')->index('productos_gestion_user_id_foreign');
            $table->unsignedBigInteger('tienda_id')->index('productos_gestion_tienda_id_foreign');
            $table->unsignedBigInteger('pedido_id')->index('productos_gestion_pedido_id_foreign');
            $table->unsignedBigInteger('estatus')->default(0);
            $table->integer('cantidad');
            $table->timestamps();
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
        Schema::dropIfExists('productos_gestion');
    }
};
