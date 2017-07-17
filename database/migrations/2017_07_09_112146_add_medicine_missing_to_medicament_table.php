<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicineMissingToMedicamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('medicaments', function(Blueprint $table)
		{
			$table->tinyInteger('is_missing')->default(0);
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
        Schema::table('medicaments', function(Blueprint $table)
		{
			$table->dropColumn(['is_missing']);
		});
    }
}
