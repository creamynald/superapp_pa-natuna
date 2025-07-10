<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('berkas_perkaras', 'jurnal_perkara_id')) {
            Schema::table('berkas_perkaras', function (Blueprint $table) {
                $table->unsignedBigInteger('jurnal_perkara_id')->nullable()->after('id');
                $table->foreign('jurnal_perkara_id')
                      ->references('id')
                      ->on('jurnal_perkaras')
                      ->onDelete('cascade');
            });
        }

        $hasNull = DB::table('berkas_perkaras')
                       ->whereNull('jurnal_perkara_id')
                       ->exists();

        if ($hasNull) {
            DB::statement('
                UPDATE berkas_perkaras bp
                JOIN jurnal_perkaras jp ON bp.nomor_perkara = jp.nomor_perkara
                SET bp.jurnal_perkara_id = jp.id
            ');
        }

        if (Schema::hasColumn('berkas_perkaras', 'jurnal_perkara_id')) {
            $hasNull = DB::table('berkas_perkaras')->whereNull('jurnal_perkara_id')->exists();
            if (!$hasNull) {
                Schema::table('berkas_perkaras', function (Blueprint $table) {
                    $table->unsignedBigInteger('jurnal_perkara_id')->nullable(false)->change();
                });
            }
        }
=
        if (Schema::hasColumn('berkas_perkaras', 'nomor_perkara')) {
            Schema::table('berkas_perkaras', function (Blueprint $table) {
                $table->dropColumn('nomor_perkara');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('berkas_perkaras', 'nomor_perkara')) {
            Schema::table('berkas_perkaras', function (Blueprint $table) {
                $table->string('nomor_perkara')->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('berkas_perkaras', 'jurnal_perkara_id')) {
            DB::statement('
                UPDATE berkas_perkaras bp
                JOIN jurnal_perkaras jp ON bp.jurnal_perkara_id = jp.id
                SET bp.nomor_perkara = jp.nomor_perkara
            ');
        }

        Schema::table('berkas_perkaras', function (Blueprint $table) {
            $table->dropForeign(['jurnal_perkara_id']);
            $table->dropColumn('jurnal_perkara_id');
        });
    }
};
