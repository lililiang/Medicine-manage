<?php

use Illuminate\Database\Seeder;

class AnagraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('anagraphs')->delete();

        for ($i=0; $i < 10; $i++) {
            \App\Anagraph::create([
                'ma_id'             => $i + 1,
                'anagraph_name'     => 'Anagraph '.$i,
                'anagraph_origin'   => '伤寒杂病论',
                'indexs'            => '[1,2]',
                'create_time'       => date('Y-m-d H:i:s', time()),
                'modify_time'       => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
