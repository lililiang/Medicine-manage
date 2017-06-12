<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropModifyStatusToMedicaments extends Migration
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
			$table->dropColumn(['need_modify']);
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
			$table->tinyInteger('need_modify')->default(0);
		});
    }
}
