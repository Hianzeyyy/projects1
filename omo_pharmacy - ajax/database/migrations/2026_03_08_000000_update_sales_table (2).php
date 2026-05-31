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
        Schema::table('sales', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('sales', 'medicine_name')) {
                $table->string('medicine_name')->after('medicine_id');
            }
            if (!Schema::hasColumn('sales', 'unit_price')) {
                $table->decimal('unit_price', 8, 2)->after('quantity');
            }
            if (!Schema::hasColumn('sales', 'total_amount')) {
                $table->decimal('total_amount', 8, 2)->after('unit_price');
            }
            if (!Schema::hasColumn('sales', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumnIfExists('medicine_name');
            $table->dropColumnIfExists('unit_price');
            $table->dropColumnIfExists('total_amount');
            $table->dropColumnIfExists('customer_name');
        });
    }
};
