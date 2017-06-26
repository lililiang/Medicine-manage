<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToMedicineAnagraphSourceRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         //
         Schema::table('anagraph_source_relations', function (Blueprint $table) {
             $table->unique('ma_id');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         //
         Schema::table('anagraph_source_relations', function (Blueprint $table) {
             $table->dropUnique('ma_id');
         });
     }
}
