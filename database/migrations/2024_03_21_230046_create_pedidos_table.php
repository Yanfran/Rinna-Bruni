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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->text('metodo_pago')->nullable();
            $table->string('key_pago')->nullable();
            $table->double('monto_total', 8, 2)->nullable();
            $table->integer('distribuidor_id')->nullable();
            $table->integer('vendedor_id')->nullable();
            $table->integer('creado_por')->nullable();
            $table->integer('estatus')->nullable();
            $table->integer('estatus_pago')->nullable();
            $table->integer('estatus_envio')->nullable();
            $table->string('tipo_envio')->nullable();
            $table->string('observacion')->nullable();
            $table->string('vale')->nullable();
            $table->string('cupon')->nullable();
            $table->string('direccion_cliente')->nullable();
            $table->string('total_cajas')->nullable();
            $table->string('aceptar_terminos')->nullable();
            $table->double('monto_cupon', 8, 2)->nullable()->default(0);
            $table->double('monto_vale', 8, 2)->nullable()->default(0);
            $table->double('monto_paqueteria', 8, 2)->nullable()->default(0);
            $table->double('monto_descuento_cliente', 8, 2)->nullable()->default(0);
            $table->double('monto_neto', 8, 2)->nullable()->default(0);
            $table->unsignedBigInteger('tipoCupon')->nullable();
            $table->double('porcentjeCuponAplicado', 8, 2)->nullable()->default(0);
            $table->double('montoCuponAplicado', 8, 2)->nullable()->default(0);
            $table->unsignedBigInteger('mercadopago_id')->nullable();
            $table->string('referencia_mercadopago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
