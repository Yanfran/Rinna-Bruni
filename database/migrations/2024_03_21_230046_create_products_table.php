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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('codigo')->nullable();
            $table->string('estilo')->nullable();
            $table->unsignedBigInteger('linea_id')->nullable()->index('products_linea_id_foreign');
            $table->string('talla')->nullable();
            $table->unsignedBigInteger('marca_id')->nullable()->index('products_marca_id_foreign');
            $table->string('ancho')->nullable();
            $table->string('color')->nullable();
            $table->string('concepto')->nullable();
            $table->string('composicion')->nullable();
            $table->unsignedBigInteger('temporada_id')->nullable()->index('products_temporada_id_foreign');
            $table->unsignedBigInteger('descripcion_id')->nullable()->index('products_descripcion_id_foreign');
            $table->decimal('costo_bruto')->nullable();
            $table->integer('descuento_1')->nullable();
            $table->integer('descuento_2')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('suela')->nullable();
            $table->string('nombre_suela')->nullable();
            $table->string('forro')->nullable();
            $table->string('horma')->nullable();
            $table->string('planilla')->nullable();
            $table->string('tacon')->nullable();
            $table->decimal('inicial')->nullable();
            $table->decimal('promedio')->nullable();
            $table->decimal('actual')->nullable();
            $table->integer('bloqueo_devolucion')->nullable();
            $table->double('precio', 8, 2)->nullable()->default(0);
            $table->string('name')->nullable();
            $table->string('detail')->nullable();
            $table->string('tipo')->nullable();
            $table->string('imagen_destacada')->nullable();
            $table->integer('estatus')->nullable();
            $table->string('url_imagen_catalogo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
