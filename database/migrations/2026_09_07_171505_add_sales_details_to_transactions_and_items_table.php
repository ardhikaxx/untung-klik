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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('is_sale')->index();
            $table->string('customer_name')->nullable()->after('source');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->decimal('discount', 15, 2)->default(0)->after('amount');
            $table->decimal('cash_received', 15, 2)->nullable()->after('payment_method');
            $table->decimal('cash_change', 15, 2)->nullable()->after('cash_received');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->default(0)->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'customer_name',
                'customer_phone',
                'discount',
                'cash_received',
                'cash_change',
            ]);
        });
    }
};
