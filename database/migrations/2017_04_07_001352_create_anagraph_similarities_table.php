<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnagraphSimilaritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anagraph_similarities', function (Blueprint $table) {
            $table->increments('mas_id');
            $table->integer('src_id');
            $table->integer('des_id');
            $table->decimal('similarity');
            $table->tinyInteger('is_del')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anagraph_similarities');
    }
}
