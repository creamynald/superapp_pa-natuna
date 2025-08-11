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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nip')->unique();
            $table->string('pangkat_golongan', 50)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tmt_golongan')->nullable();
            $table->string('jabatan', 150)->nullable();
            $table->date('tmt_pegawai')->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->year('tahun_pendidikan')->nullable();
            $table->date('kgb_yad')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
