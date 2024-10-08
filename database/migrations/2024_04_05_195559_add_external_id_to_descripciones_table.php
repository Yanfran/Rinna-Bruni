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
        Schema::table('descripciones', function (Blueprint $table) {
            $table->integer('external_id')->nullable()->after('estatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('descripciones', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
