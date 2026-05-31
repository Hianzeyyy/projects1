<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'inventory_id')) {
                $table->foreignId('inventory_id')->nullable()->after('user_id')->constrained('inventory')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'transaction_type')) {
                $table->string('transaction_type')->default('sale')->after('inventory_id');
            }

            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->enum('payment_method', ['Cash', 'GCash'])->default('Cash')->after('transaction_type');
            }

            if (!Schema::hasColumn('transactions', 'total_price')) {
                $table->decimal('total_price', 12, 2)->default(0)->after('payment_method');
            }

            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('total_price');
            }

            if (!Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('tax_amount');
            }
        });

        if (Schema::hasColumn('transactions', 'amount')) {
            DB::table('transactions')
                ->where('total_price', 0)
                ->update(['total_price' => DB::raw('amount')]);
        }

        if (Schema::hasColumn('transactions', 'type')) {
            DB::table('transactions')
                ->where('transaction_type', 'sale')
                ->update(['transaction_type' => DB::raw('type')]);
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'inventory_id')) {
                $table->dropConstrainedForeignId('inventory_id');
            }
            if (Schema::hasColumn('transactions', 'transaction_type')) {
                $table->dropColumn('transaction_type');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('transactions', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('transactions', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }
            if (Schema::hasColumn('transactions', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
