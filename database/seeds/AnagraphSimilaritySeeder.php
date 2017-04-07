<?php

use Illuminate\Database\Seeder;

class AnagraphSimilaritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('anagraph_similarities')->delete();

        for ($i=0; $i < 10; $i++) {
            for ($j=0; $j <= $i; $j++) {
                \App\AnagraphSimilarity::create([
                    'mas_id'        => $i * 10 + $j + 1,
                    'src_id'        => $i + 1,
                    'des_id'        => $j + 1,
                    'similarity'    => mt_srand(0, 100) / 100,
                    'is_del'        => 0
                ]);
            }
        }
    }
}
