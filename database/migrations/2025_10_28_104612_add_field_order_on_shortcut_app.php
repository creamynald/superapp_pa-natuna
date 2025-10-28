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
        Schema::table('shortcut_apps', function (Blueprint $table) {
            $table->unsignedInteger('order')->after('path')->nullable()->comment('Nomor urut tampilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortcut_apps', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
