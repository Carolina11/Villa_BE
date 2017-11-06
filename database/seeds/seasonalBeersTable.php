<?php

use Illuminate\Database\Seeder;

class seasonalBeersTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('seasonalBeers')->insert([
            'name' => 'Samuel Adams Seasonal',
            'beerName' => 'Samuel Adams Octoberfest',
      ]);
      DB::table('seasonalBeers')->insert([
            'name' => 'Traveling Beer Tap',
            'beerName' => 'Shocktop Belgian White',
      ]);
    }
}
