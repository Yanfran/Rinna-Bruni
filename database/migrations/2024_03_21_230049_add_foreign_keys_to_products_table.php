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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['descripcion_id'])->references(['id'])->on('descripciones')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['linea_id'])->references(['id'])->on('lineas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['marca_id'])->references(['id'])->on('marcas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['temporada_id'])->references(['id'])->on('temporadas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_descripcion_id_foreign');
            $table->dropForeign('products_linea_id_foreign');
            $table->dropForeign('products_marca_id_foreign');
            $table->dropForeign('products_temporada_id_foreign');
        });
    }
};
