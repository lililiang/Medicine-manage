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
        $this->call(DiseaseSeeder::class);
        $this->call(DiseaseAliasSeeder::class);
        $this->call(DiseaseAliasRelationSeeder::class);
        $this->call(DiseaseTypeSeeder::class);
        $this->call(DiseaseTypeRelationSeeder::class);
        $this->call(TcmSyndromeSeeder::class);
        $this->call(TcmSyndromeAliasSeeder::class);
        $this->call(TcmSyndromeAliasRelationSeeder::class);
        $this->call(TcmSyndromeDiseaseRelationSeeder::class);
    }
}
