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
            // Tambahkan kolom baru
            $table->unsignedBigInteger('jurnal_perkara_id')->nullable()->after('id');

            // Tambahkan foreign key
            $table->foreign('jurnal_perkara_id')
                ->references('id')
                ->on('jurnal_perkaras')
                ->onDelete('cascade');
        });

        // Isi data jurnal_perkara_id berdasarkan nomor_perkara (hanya jika sudah ada data)
        \DB::statement('
            UPDATE data_saksis ds
            JOIN jurnal_perkaras jp ON ds.nomor_perkara = jp.nomor_perkara
            SET ds.jurnal_perkara_id = jp.id
        ');

        // Setelah isi, ubah jurnal_perkara_id menjadi required
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->unsignedBigInteger('jurnal_perkara_id')->nullable(false)->change();
        });

        // Hapus kolom lama (opsional)
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->dropColumn('nomor_perkara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->string('nomor_perkara')->nullable();
            $table->dropForeign(['jurnal_perkara_id']);
            $table->dropColumn('jurnal_perkara_id');
        });
    }
};
