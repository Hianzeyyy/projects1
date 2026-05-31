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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // nullable for anonymous
            $table->string('alias')->nullable(); // alias for students/faculty
            $table->text('description');
            $table->enum('status', ['unread', 'investigating', 'resolved', 'closed'])->default('unread');
            $table->timestamps();
        });

        Schema::create('report_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });

        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('alias');
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected', 'responded'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->timestamps();
        });

        Schema::create('suggestion_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suggestion_id')->constrained('suggestions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('alias');
            $table->unique(['suggestion_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('evidence_vault', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->string('original_name');
            $table->timestamps();
        });

        Schema::create('safewalk_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->boolean('panic_triggered')->default(false);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('safewalk_checkins');
        Schema::dropIfExists('evidence_vault');
        Schema::dropIfExists('suggestion_upvotes');
        Schema::dropIfExists('suggestions');
        Schema::dropIfExists('report_evidence');
        Schema::dropIfExists('reports');
    }
};
