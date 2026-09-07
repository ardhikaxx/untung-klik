<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users table (no FK yet)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('phone')->nullable();
            $table->string('pin_hash');
            $table->enum('role', ['owner', 'admin', 'karyawan'])->default('karyawan');
            $table->boolean('is_active')->default(true);
            $table->timestamp('pin_changed_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Businesses table
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Add business_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->after('id')->constrained('businesses')->nullOnDelete();
        });

        // 4. Transaction categories
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['masuk', 'keluar', 'operasional'])->default('masuk');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->enum('type', ['masuk', 'keluar']);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('source')->nullable();
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });

        // 6. Capital entries
        Schema::create('capital_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('entry_date');
            $table->string('source')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. Operational expenses
        Schema::create('operational_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 8. Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('phone')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 9. Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('operational_expenses');
        Schema::dropIfExists('capital_entries');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropColumn('business_id');
        });
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('users');
    }
};
