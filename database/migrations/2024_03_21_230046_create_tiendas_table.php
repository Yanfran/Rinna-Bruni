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
        Schema::create('tiendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('codigo')->nullable();
            $table->integer('pais_id')->nullable()->default(1);
            $table->integer('estado_id')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->integer('localidad_id')->nullable();
            $table->integer('estatus')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->string('calle_numero')->nullable();
            $table->string('cp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiendas');
    }
};
