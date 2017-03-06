<?php

use Illuminate\Database\Seeder;

class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         //
         DB::table('medicaments')->delete();

         for ($i=0; $i < 10; $i++) {
             \App\Medicament::create([
                 'medicine_name' => 'Medicine '.$i,
                 'create_time'   => date('Y-m-d H:i:s', time()),
                 'modify_time'   => date('Y-m-d H:i:s', time())
             ]);
         }
     }
}
