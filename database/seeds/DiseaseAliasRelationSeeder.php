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
            for ($j = 0; $j < 10; $j++) {
                \App\DiseaseAliasRelation::create([
                    'mdar_id'   => $i * 10 + $j + 1,
                    'md_id'     => $i + 1,
                    'mda_id'    => $j + 1
                ]);
            }
        }
    }
}
