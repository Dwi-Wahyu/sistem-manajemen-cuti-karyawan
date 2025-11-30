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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Siapa pelakunya (bisa null jika sistem)
            $table->string('action'); // create, update, delete, restore
            $table->string('description')->nullable(); // Deskripsi singkat

            // Polymorphic columns (model_type & model_id)
            // Contoh: App\Models\LeaveRequest, ID: 5
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            // Menyimpan data sebelum dan sesudah perubahan (format JSON)
            $table->json('properties')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Foreign key ke users (opsional, set null on delete agar log tidak hilang jika user dihapus)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Index untuk mempercepat pencarian
            $table->index(['subject_type', 'subject_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
