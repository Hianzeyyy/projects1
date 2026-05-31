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
    Schema::create('inventory', function (Blueprint $table) {
        $table->id();
        $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
        $table->string('medicine_name');
        $table->integer('quantity');
        $table->string('batch_number');
        $table->date('expiry_date');
        $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        $table->string('supplier_name');
        $table->integer('reorder_level');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
