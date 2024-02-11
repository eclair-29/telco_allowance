<?php

namespace Database\Seeders;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        $status_id = Status::where('category', 'plan')
            ->where('description', 'active')
            ->first()
            ->id;

        $plans = [
            ['provider' => 'SMART', 'description' => 'Enterprise Postpaid Plan 100', 'type' => 'Postpaid', 'subscription_fee' => 89.29, 'status_id' => $status_id, 'created_at' => $date],
            ['provider' => 'SMART', 'description' => 'Enterprise Postpaid Plan 500', 'type' => 'Postpaid', 'subscription_fee' => 446.43, 'status_id' => $status_id, 'created_at' => $date],
            ['provider' => 'SMART', 'description' => 'Enterprise Postpaid Plan 1000', 'type' => 'Postpaid', 'subscription_fee' => 892.86, 'status_id' => $status_id, 'created_at' => $date],
            ['provider' => 'SMART', 'description' => 'Enterprise Postpaid Plan 1500', 'type' => 'Postpaid', 'subscription_fee' => 1339.29, 'status_id' => $status_id, 'created_at' => $date],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->insert($plan);
        }
    }
}
