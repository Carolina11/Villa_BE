<?php

use Illuminate\Database\Seeder;

class menusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('menus')->insert([
        'name' => 'Lunch',
        ]);

        DB::table('menus')->insert([
        'name' => 'Dinner',
        ]);

        DB::table('menus')->insert([
        'name' => 'Libations',
        ]);
    }
}
