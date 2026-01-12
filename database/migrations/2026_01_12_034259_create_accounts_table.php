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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('account_type', ['income', 'expense']); // income from tenants, expense for costs
            $table->enum('category', [
                // Income categories
                'rental_income',
                'deposit',
                'late_fee',
                'other_income',
                // Expense categories - Fixed Costs
                'rent_building',
                'insurance',
                'property_tax',
                'salary',
                'loan_payment',
                'depreciation',
                // Expense categories - Variable Costs
                'utilities',
                'maintenance',
                'cleaning',
                'supplies',
                'marketing',
                'repairs',
                // Bank/Financial
                'bank_fee',
                'bank_interest',
                'bank_transfer',
                // Other
                'other_expense'
            ]);
            $table->enum('cost_type', ['fixed', 'variable', 'bank', 'income'])->default('variable');
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->integer('month');
            $table->integer('year');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null'); // Link to tenant payment
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who recorded it
            $table->string('reference_number')->nullable(); // For bank transactions
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
