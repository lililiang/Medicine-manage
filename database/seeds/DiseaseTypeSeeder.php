<?php

use Illuminate\Database\Seeder;

class DiseaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('disease_types')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\DiseaseType::create([
                'mdt_id'        => $i + 1,
                'type_name'     => 'Type '.$i,
                'type_desc'     => 'Type desc'.$i,
                'create_time'   => date('Y-m-d H:i:s', time()),
                'modify_time'   => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
