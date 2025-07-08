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
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->dropUnique(['nomor_perkara']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->unique('nomor_perkara')->change();
        });
    }
};
