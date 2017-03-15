<?php

use Illuminate\Database\Seeder;

class AnagraphComposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         //
         DB::table('anagraph_composes')->delete();

         for ($i = 1; $i <= 10; $i++) {
             for ($j = 1; $j <= 10; $j++) {
                 \App\AnagraphCompose::create([
                     'mac_id'       => $i * 10 + $j +1,
                     'ma_id'        => $i,
                     'mm_id'        => $j,
                     'dosage'       => '一两',
                     'usage'        => '["\u7092","\u64d8"]',
                     'create_time'  => date('Y-m-d H:i:s', time()),
                     'modify_time'  => date('Y-m-d H:i:s', time()),
                 ]);
             }
         }
     }
}
