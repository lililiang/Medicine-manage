<?php

use Illuminate\Database\Seeder;

class TcmSyndromeDiseaseRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tcm_syndrome_disease_relations')->delete();

        for ($i=0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                \App\TcmSyndromeDiseaseRelation::create([
                    'mtsdr_id'  => $i * 10 + $j + 1,
                    'mts_id'    => $i + 1,
                    'md_id'   => $j + 1
                ]);
            }
        }
    }
}
