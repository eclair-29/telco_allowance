<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        $positions = [
            ['description' => 'Manager', 'created_at' => $date],
            ['description' => 'Senior Manager', 'created_at' => $date],
            ['description' => 'Group Manager', 'created_at' => $date],
            ['description' => 'Senior Group Manager', 'created_at' => $date],
            ['description' => 'General Manager', 'created_at' => $date],
            ['description' => 'Senior Specialist', 'created_at' => $date],
            ['description' => 'Chief Specialist', 'created_at' => $date],
            ['description' => 'Specialist II', 'created_at' => $date],
            ['description' => 'Vice President', 'created_at' => $date],
        ];

        foreach ($positions as $position) {
            DB::table('positions')->insert($position);
        }
    }
}
