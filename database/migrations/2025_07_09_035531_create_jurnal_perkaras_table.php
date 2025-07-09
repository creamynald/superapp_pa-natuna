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
        Schema::create('jurnal_perkaras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_perkara')->unique();
            $table->string('klasifikasi_perkara');
            $table->text('penggugat');
            $table->text('tergugat');
            $table->string('proses_terakhir')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_perkaras');
    }
};
