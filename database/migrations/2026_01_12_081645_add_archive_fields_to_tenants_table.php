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
        Schema::table('tenants', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('notes');
            $table->text('leave_reason')->nullable()->after('archived_at');
            $table->decimal('final_utility_charges', 10, 2)->default(0)->after('leave_reason');
            $table->decimal('final_other_charges', 10, 2)->default(0)->after('final_utility_charges');
            $table->decimal('total_rent_paid', 10, 2)->default(0)->after('final_other_charges');
            $table->text('invoice_notes')->nullable()->after('total_rent_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'archived_at', 
                'leave_reason', 
                'final_utility_charges', 
                'final_other_charges',
                'total_rent_paid',
                'invoice_notes'
            ]);
        });
    }
};
