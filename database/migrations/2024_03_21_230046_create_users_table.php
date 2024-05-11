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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('rol')->default(2);
            $table->integer('tipo')->nullable()->default(1);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('estatus')->nullable()->default(1);
            $table->string('user_rfc', 100)->nullable();
            $table->timestamps();
            $table->string('usuario')->nullable();
            $table->string('numero_empleado')->nullable();
            $table->unsignedBigInteger('tienda_id')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->decimal('descuento')->nullable();
            $table->text('credito')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('numero_afiliacion')->nullable();
            $table->string('nombre_empresa')->nullable();
            $table->string('rfc')->nullable();
            $table->string('regimen_fiscal')->nullable();
            $table->string('dia_credito')->nullable();
            $table->string('descuento_oferta')->nullable();
            $table->string('descuento_outlet')->nullable();
            $table->integer('distribuidor_id')->nullable();
            $table->integer('bloqueo_pedido')->nullable();
            $table->integer('cuentas_creadas')->nullable();
            $table->integer('cuentas_restantes')->nullable();
            $table->integer('descuento_clientes')->nullable();
            $table->integer('dias_devolucion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
