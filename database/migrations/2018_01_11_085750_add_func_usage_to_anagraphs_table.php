<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFuncUsageToAnagraphsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('anagraphs', function(Blueprint $table)
		{
            $table->string('author');
            $table->text('func');
            $table->text('usage');
            $table->string('inference');
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
        Schema::table('anagraphs', function(Blueprint $table)
		{
			$table->dropColumn(['author', 'func', 'usage', 'inference']);
		});
    }
}
