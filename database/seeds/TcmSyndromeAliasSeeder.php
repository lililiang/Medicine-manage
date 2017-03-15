<?php

use Illuminate\Database\Seeder;

class TcmSyndromeAliasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        DB::table('tcm_syndrome_aliases')->delete();

        for ($i = 0; $i < 10; $i++) {
            \App\TcmSyndromeAlias::create([
                'mtsa_id'           => $i + 1,
                'syndrome_alias'    => 'Syndrome Alias '.$i,
                'create_time'       => date('Y-m-d H:i:s', time()),
                'modify_time'       => date('Y-m-d H:i:s', time())
            ]);
        }
    }
}
