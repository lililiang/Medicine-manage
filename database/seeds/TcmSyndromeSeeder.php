<?php

use Illuminate\Database\Seeder;

class TcmSyndromeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tcm_syndromes')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\TcmSyndrome::create([
                'mts_id'            => $i + 1,
                'syndrome_name'     => 'Syndrome ' . $i,
                'syndrome_desc'     => 'Syndrome`s Desc ' . $i,
                'create_time'       => date('Y-m-d H:i:s', time()),
                'modify_time'       => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
