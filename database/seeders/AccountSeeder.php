<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = now()->year;
        
        // Sample data for multiple months
        $entries = [
            // January Income
            ['income', 'rental_income', 'income', 'Monthly rental payments', 15000, 1],
            ['income', 'deposit', 'income', 'Security deposit - New tenant', 2000, 1],
            
            // January Fixed Costs
            ['expense', 'rent_building', 'fixed', 'Building mortgage payment', 5000, 1],
            ['expense', 'insurance', 'fixed', 'Property insurance', 500, 1],
            ['expense', 'salary', 'fixed', 'Staff salary', 3000, 1],
            
            // January Variable Costs
            ['expense', 'utilities', 'variable', 'Electric and water bills', 800, 1],
            ['expense', 'maintenance', 'variable', 'General maintenance', 400, 1],
            ['expense', 'cleaning', 'variable', 'Cleaning service', 300, 1],
            
            // January Bank Costs
            ['expense', 'bank_fee', 'bank', 'Monthly bank fees', 50, 1],
            
            // February entries
            ['income', 'rental_income', 'income', 'Monthly rental payments', 15500, 2],
            ['expense', 'rent_building', 'fixed', 'Building mortgage payment', 5000, 2],
            ['expense', 'salary', 'fixed', 'Staff salary', 3000, 2],
            ['expense', 'utilities', 'variable', 'Electric and water bills', 750, 2],
            ['expense', 'repairs', 'variable', 'Plumbing repair - Unit 201', 600, 2],
            ['expense', 'bank_fee', 'bank', 'Monthly bank fees', 50, 2],
            
            // March entries
            ['income', 'rental_income', 'income', 'Monthly rental payments', 16000, 3],
            ['income', 'late_fee', 'income', 'Late payment fees', 150, 3],
            ['expense', 'rent_building', 'fixed', 'Building mortgage payment', 5000, 3],
            ['expense', 'insurance', 'fixed', 'Property insurance - Quarterly', 500, 3],
            ['expense', 'salary', 'fixed', 'Staff salary', 3000, 3],
            ['expense', 'utilities', 'variable', 'Electric and water bills', 700, 3],
            ['expense', 'marketing', 'variable', 'Advertising for vacant units', 200, 3],
            ['expense', 'bank_fee', 'bank', 'Monthly bank fees', 50, 3],
        ];

        foreach ($entries as $entry) {
            Account::create([
                'account_type' => $entry[0],
                'category' => $entry[1],
                'cost_type' => $entry[2],
                'description' => $entry[3],
                'amount' => $entry[4],
                'transaction_date' => Carbon::create($year, $entry[5], rand(1, 28)),
                'month' => $entry[5],
                'year' => $year,
            ]);
        }
    }
}
