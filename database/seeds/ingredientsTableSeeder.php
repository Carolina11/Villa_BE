<?php

use Illuminate\Database\Seeder;

class ingredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('ingredients')->insert([
        'name' => 'Chicken',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Pork',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Beef',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Lamb',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Seafood',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Fish',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Turkey',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Cheese',
        ]);

        DB::table('ingredients')->insert([
        'name' => 'Duck',
        ]);
    }
}
