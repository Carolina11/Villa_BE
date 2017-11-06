<?php

use Illuminate\Database\Seeder;

class beersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('beers')->insert([
            'name' => 'Samuel Adams Seasonal',
            'beerName' => 'Samuel Adams Octoberfest',
      ]);
      DB::table('beers')->insert([
            'name' => 'Traveling Beer Tap',
            'beerName' => 'Shocktop Belgian White',
      ]);
    }
}
