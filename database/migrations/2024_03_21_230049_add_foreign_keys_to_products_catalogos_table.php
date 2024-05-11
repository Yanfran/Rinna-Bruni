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
        Schema::table('products_catalogos', function (Blueprint $table) {
            $table->foreign(['catalogo_id'])->references(['id'])->on('catalogos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_catalogos', function (Blueprint $table) {
            $table->dropForeign('products_catalogos_catalogo_id_foreign');
            $table->dropForeign('products_catalogos_product_id_foreign');
        });
    }
};
