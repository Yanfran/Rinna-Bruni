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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pedido_id')->nullable()->index('notifications_pedido_id_foreign');
            $table->string('titulo');
            $table->text('mensaje');
            $table->unsignedBigInteger('user_id')->nullable()->index('notifications_user_id_foreign');
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('usuario_creado')->nullable()->index('notifications_usuario_creado_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
