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
        Schema::create('data_saksis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_perkara')->unique();
            $table->string('dari_pihak');
            $table->string('nama_lengkap');
            $table->string('bin_binti');
            $table->string('tempat_tanggal_lahir');
            $table->char('nik', 16);
            $table->string('no_hp');
            $table->string('email');
            $table->text('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pekerjaan');
            $table->enum('pendidikan', [
                'Tidak Sekolah',
                'SD',
                'SLTP',
                'SLTA',
                'Diploma Satu (D1)',
                'Diploma Dua (D2)',
                'Diploma Tiga (D3)',
                'Sarjana (S1)',
                'Magister (S2)',
                'Doktor (S3)'
            ])->default('Tidak Sekolah');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya']);
            $table->enum('status_kawin', ['Kawin', 'Belum Kawin', 'Duda', 'Janda']);
            $table->enum('hubungan_dengan_penggugat_tergugat', ['Ayah', 'Ibu', 'Adik', 'Kakak', 'Paman', 'Bibi', 'Saudara', 'Lainnya']);
            $table->enum('pernah_lihat_bertengkar', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('status_pisah_rumah', ['Ya', 'Tidak'])->default('Tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_saksis');
    }
};
