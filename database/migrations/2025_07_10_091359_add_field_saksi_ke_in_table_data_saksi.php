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
            $table->integer('saksi_ke')->unsigned()->default(1)->after('bin_binti');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_saksis', function (Blueprint $table) {
            $table->dropColumn('saksi_ke');
        });
    }
};
