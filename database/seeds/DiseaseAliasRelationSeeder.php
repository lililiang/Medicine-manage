<?php

use Illuminate\Database\Seeder;

class DiseaseAliasRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('disease_alias_relations')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\DiseaseAliasRelation::create([
                
            ]);
        }
    }
}
