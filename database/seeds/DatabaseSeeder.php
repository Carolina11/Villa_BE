<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ingredientsTableSeeder::class);
        $this->call(menusTableSeeder::class);
        $this->call(typesTableSeeder::class);
        $this->call(seasonalBeersTable::class);
        $this->call(specialsTableSeeder::class);
    }
}
