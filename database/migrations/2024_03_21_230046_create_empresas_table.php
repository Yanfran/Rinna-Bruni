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
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('nombre')->nullable();
            $table->longText('logo')->nullable();
            $table->longText('email')->nullable();
            $table->longText('telefono_2')->nullable();
            $table->longText('telefono_1')->nullable();
            $table->longText('colorPrimario')->nullable();
            $table->longText('colorSecundario')->nullable();
            $table->longText('direccion')->nullable();
            $table->integer('estatus')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->string('inactividad')->nullable();
            $table->string('mp_public_key')->nullable();
            $table->string('mp_access_token')->nullable();
            $table->decimal('costo_paqueteria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
};
