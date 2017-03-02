<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnagraphComposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anagraph_composes', function (Blueprint $table) {
            $table->increments('mac_id');
            $table->integer('ma_id');
            $table->integer('mm_id');
            $table->string('dosage');
            $table->text('usage');
            $table->timestamp('create_time')->nullable();
            $table->timestamp('modify_time')->nullable();
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
        Schema::dropIfExists('anagraph_composes');
    }
}
