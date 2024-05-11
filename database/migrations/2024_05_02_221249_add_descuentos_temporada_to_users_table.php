<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->float('descuento_1')->nullable()->default(0);
            $table->float('descuento_2')->nullable()->default(0);
            $table->float('descuento_3')->nullable()->default(0);
            $table->float('descuento_4')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('descuento_1');
            $table->dropColumn('descuento_2');
            $table->dropColumn('descuento_3');
            $table->dropColumn('descuento_4');
        });
    }
};
