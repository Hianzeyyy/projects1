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
        Schema::create('drug_interactions', function (Blueprint $table): void {
            $table->id();
            $table->string('ingredient');
            $table->string('conflicting_ingredient');
            $table->enum('severity', ['warning', 'critical'])->default('warning');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['ingredient', 'conflicting_ingredient']);
            $table->index('ingredient');
            $table->index('conflicting_ingredient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_interactions');
    }
};
