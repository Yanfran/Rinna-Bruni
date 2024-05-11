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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('alias');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('municipio_id');
            $table->unsignedBigInteger('localidad_id');
            $table->string('calle');
            $table->unsignedBigInteger('user_id')->index('direcciones_user_id_foreign');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('tipo')->nullable()->default(0);
            $table->unsignedBigInteger('estatus')->nullable()->default(1);
            $table->string('nombre_encargado')->nullable();
            $table->string('celular')->nullable();
            $table->text('telefono_fijo')->nullable();
            $table->string('cp')->nullable();
            $table->string('colonia')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('correo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direcciones');
    }
};
