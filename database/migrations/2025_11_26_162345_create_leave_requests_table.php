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
            $table->unsignedBigInteger('user_id'); // Karyawan yang mengajukan
            $table->unsignedBigInteger('leave_type_id'); // Jenis Cuti
            
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 4, 1); // Total hari cuti (bisa desimal jika ada cuti setengah hari)
            $table->text('reason');
            $table->string('contact_address_during_leave')->nullable();
            $table->string('emergency_number')->nullable();
            $table->string('medical_certificate_path')->nullable(); // Path surat dokter
            
            // Alur Persetujuan
            $table->enum('status', ['pending', 'approved_by_leader', 'approved', 'rejected', 'cancelled'])->default('pending');
            
            // Verifikasi Ketua Divisi
            $table->unsignedBigInteger('leader_approver_id')->nullable();
            $table->timestamp('leader_approved_at')->nullable();
            $table->text('leader_rejection_note')->nullable();
            
            // Persetujuan Final HRD
            $table->unsignedBigInteger('hrd_approver_id')->nullable();
            $table->timestamp('hrd_approved_at')->nullable();
            $table->text('hrd_rejection_note')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('restrict');
            $table->foreign('leader_approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('hrd_approver_id')->references('id')->on('users')->onDelete('set null');

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
