<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        $actions = [
            ['resource' => 'publisher', 'description' => 'generate worksheet', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'draft worksheet', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'publish worksheet', 'created_at' => $date],

            ['resource' => 'publisher', 'description' => 'add plan', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'deactivate plan', 'created_at' => $date],

            ['resource' => 'publisher', 'description' => 'update profile', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'deactivate profile', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'add profile', 'created_at' => $date],

            ['resource' => 'publisher', 'description' => 'add loan', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'update loan', 'created_at' => $date],
            ['resource' => 'publisher', 'description' => 'cancel loan', 'created_at' => $date],

            ['resource' => 'approver', 'description' => 'approve worksheet', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'approve add profile', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'approve update profile', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'approve add loan', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'approve update loan', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'approve cancel loan', 'created_at' => $date],
            ['resource' => 'approver', 'description' => 'reject request', 'created_at' => $date],
        ];

        foreach ($actions as $action) {
            DB::table('actions')->insert($action);
        }
    }
}
