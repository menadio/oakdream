<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Plan::create(['name' => 'Reducing Balance']);
        App\Plan::create(['name' => 'Over Period']);
    }
}
