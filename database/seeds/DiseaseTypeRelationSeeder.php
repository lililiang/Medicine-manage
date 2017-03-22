<?php

use Illuminate\Database\Seeder;

class DiseaseTypeRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('disease_type_relations')->delete();

        for ($i=0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                \App\DiseaseTypeRelation::create([
                    'mdtr_id'   => $i * 10 + $j + 1,
                    'md_id'     => $j + 1,
                    'mdt_id'    => $i + 1
                ]);
            }
        }
    }
}
