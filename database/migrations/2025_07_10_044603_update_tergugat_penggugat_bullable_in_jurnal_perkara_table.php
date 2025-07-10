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
        Schema::table('jurnal_perkaras', function (Blueprint $table) {
            $table->text('tergugat')->nullable()->change();
            $table->text('penggugat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_perkaras', function (Blueprint $table) {
            $table->text('tergugat')->nullable(false)->change();
            $table->text('penggugat')->nullable(false)->change();
        });
    }
};
