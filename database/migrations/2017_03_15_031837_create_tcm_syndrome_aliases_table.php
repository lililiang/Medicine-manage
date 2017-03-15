<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcmSyndromeAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tcm_syndrome_aliases', function (Blueprint $table) {
            $table->increments('mtsa_id');
            $table->string('syndrome_alias');
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
        Schema::dropIfExists('tcm_syndrome_aliases');
    }
}
