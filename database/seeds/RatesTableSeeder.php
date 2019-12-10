<?php

use Illuminate\Database\Seeder;

class RatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Rate::create(['interest' => 7]);
        App\Rate::create(['interest' => 12]);
    }
}
