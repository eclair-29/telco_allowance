<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        $statuses = [
            // plan status
            ['category' => 'plan', 'description' => 'active', 'created_at' => $date],
            ['category' => 'plan', 'description' => 'inactive', 'created_at' => $date],

            // assignee status
            ['category' => 'assignee', 'description' => 'active', 'created_at' => $date],
            ['category' => 'assignee', 'description' => 'inactive', 'created_at' => $date],

            // loan status
            ['category' => 'loan', 'description' => 'cancelled', 'created_at' => $date],
            ['category' => 'loan', 'description' => 'ongoing', 'created_at' => $date],
            ['category' => 'loan', 'description' => 'finished', 'created_at' => $date],

            // consolidated excess billing status
            ['category' => 'excess', 'description' => 'draft', 'created_at' => $date],
            ['category' => 'excess', 'description' => 'published', 'created_at' => $date],

            // requests status
            ['category' => 'request', 'description' => 'pending',  'created_at' => $date],
            ['category' => 'request', 'description' => 'approved', 'created_at' => $date],
            ['category' => 'request', 'description' => 'rejected', 'created_at' => $date],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insert($status);
        }
    }
}
