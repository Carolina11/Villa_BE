<?php

use Illuminate\Database\Seeder;

class typesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('types')->insert([
        'name' => 'Soup',
        ]);

        DB::table('types')->insert([
        'name' => 'Appetizer',
        ]);

        DB::table('types')->insert([
        'name' => 'Entree',
        ]);

        DB::table('types')->insert([
        'name' => 'Dessert',
        ]);

        DB::table('types')->insert([
        'name' => 'Beer',
        ]);

        DB::table('types')->insert([
        'name' => 'Libations',
        ]);

    }
}
