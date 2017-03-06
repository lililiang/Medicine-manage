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
        $this->call(AnagraphSeeder::class);
        $this->call(AnagraphComposeSeeder::class);
        $this->call(MedicamentSeeder::class);
    }
}