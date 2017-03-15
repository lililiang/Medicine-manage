<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcmSyndromesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tcm_syndromes', function (Blueprint $table) {
            $table->increments('mts_id');
            $table->string('syndrome_name');
            $table->string('syndrome_desc');
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
        Schema::dropIfExists('tcm_syndromes');
    }
}
