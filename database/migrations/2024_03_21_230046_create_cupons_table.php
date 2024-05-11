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
        Schema::create('cupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('codigo')->nullable();
            $table->double('monto', 8, 2)->nullable()->default(0);
            $table->text('porcentaje')->nullable();
            $table->text('cantidad_aplicacion')->nullable();
            $table->integer('tipo')->nullable();
            $table->integer('estatus');
            $table->timestamps();
            $table->softDeletes();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('cantidad_usos')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('cupons_user_id_foreign');
            $table->unsignedBigInteger('tipoCupon')->nullable();
            $table->double('porcentjeCuponAplicado', 8, 2)->nullable()->default(0);
            $table->double('montoCuponAplicado', 8, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupons');
    }
};
