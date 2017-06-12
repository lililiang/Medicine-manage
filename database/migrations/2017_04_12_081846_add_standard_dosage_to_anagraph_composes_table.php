<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStandardDosageToAnagraphComposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('anagraph_composes', function(Blueprint $table)
		{
			$table->decimal('standard_dosage')->default(0.0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anagraph_composes', function(Blueprint $table)
		{
			$table->dropColumn(['standard_dosage']);
		});
    }
}
