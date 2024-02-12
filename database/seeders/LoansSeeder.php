<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        $loans = [
            ['assignee_id' => 12, 'total_subscription_count' => 23, 'current_subscription_count' => 23, 'subscription_fee' => 2142.85, 'status_id' => 6, 'created_at' => $date],
        ];

        foreach ($loans as $loan) {
            DB::table('loans')->insert($loan);
        }
    }
}
