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
        Schema::create('balance_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_period_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['asset', 'liability', 'equity']);
            $table->enum('sub_type', [
                // Assets
                'current_asset',      // Cash, Accounts Receivable, etc.
                'fixed_asset',        // Property, Equipment, etc.
                // Liabilities
                'current_liability',  // Accounts Payable, Short-term loans
                'long_term_liability', // Mortgages, Long-term loans
                // Equity
                'owner_equity',       // Owner's Capital
                'retained_earnings',  // Retained Earnings
            ]);
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('as_of_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_sheet_items');
    }
};
