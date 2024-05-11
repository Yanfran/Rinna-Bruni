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
        Schema::create('products_catalogos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('catalogo_id')->index('products_catalogos_catalogo_id_foreign');
            $table->unsignedBigInteger('product_id')->index('products_catalogos_product_id_foreign');
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
        Schema::dropIfExists('products_catalogos');
    }
};
