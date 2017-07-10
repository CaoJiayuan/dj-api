<?php

use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ads')->insert(['image'=>'0869adc479c578d7045e76e43ccec6a7','image'=>'e06a9a1afd0b4548151406f9d5e1e41e']);
    }
}
