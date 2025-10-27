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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // pemohon = user login
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('duration_days'); // (end - start) + 1
            $table->text('reason')->nullable();
            $table->text('address_on_leave')->nullable();
            $table->string('phone_on_leave')->nullable();
            $table->enum('status', [
                'draft','submitted','manager_approved','manager_rejected','final_approved','final_rejected','cancelled'
            ])->default('draft');
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->foreignId('final_approver_id')->nullable()->constrained('users');
            $table->text('manager_note')->nullable();
            $table->text('final_note')->nullable();
            $table->string('doc_number')->nullable(); // nomor surat saat final_approved

            // snapshot identitas dari users + employees
            $table->json('employee_snapshot')->nullable(); // {name,nip,jabatan,gol,masa_kerja}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
