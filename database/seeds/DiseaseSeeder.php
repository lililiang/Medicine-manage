<?php

use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('diseases')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\Disease::create([
                'disease_name' => 'Disease '.$i,
                'disease_desc' => '',
                'create_time'       => date('Y-m-d H:i:s', time()),
                'modify_time'       => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
