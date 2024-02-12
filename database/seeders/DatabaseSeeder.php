<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\StatusesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            StatusesSeeder::class,
            RolesSeeder::class,
            PositionsSeeder::class,
            PlansSeeder::class,
            AssigneesSeeder::class,
            ActionsSeeder::class,
            LoansSeeder::class
        ]);
    }
}
