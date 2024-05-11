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
        Schema::create('mercadopago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('referencia')->nullable();
            $table->decimal('precio');
            $table->unsignedBigInteger('pedido_id')->index('mercadopago_pedido_id_foreign');
            $table->timestamps();
            $table->string('collection_id')->nullable();
            $table->string('collection_status')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('status')->nullable();
            $table->string('external_reference')->nullable();
            $table->string('payment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercadopago');
    }
};
