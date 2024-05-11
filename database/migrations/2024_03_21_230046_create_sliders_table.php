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
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('titulo_es')->nullable();
            $table->longText('descripcion_es')->nullable();
            $table->longText('titulo_en')->nullable();
            $table->longText('descripcion_en')->nullable();
            $table->longText('imagen')->nullable();
            $table->integer('estatus')->nullable()->default(0);
            $table->unsignedInteger('empresas_id')->index('sliders_empresas_id_foreign')->comment('relacion con la tabla de emprsa');
            $table->softDeletes();
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
        Schema::dropIfExists('sliders');
    }
};
