<?php

use Illuminate\Database\Seeder;

class DiseaseAliasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('disease_aliases')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\DiseaseAlias::create([
                'md_id'         => $i,
                'disease_alias' => 'Disease Alias '.$i,
                'create_time'   => date('Y-m-d H:i:s', time()),
                'modify_time'   => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
