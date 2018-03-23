<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicineComposes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_composes', function (Blueprint $table) {
            $table->increments('mmc_id');
            $table->integer('mm_id');
            $table->integer('mmi_id');
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
        //
        Schema::dropIfExists('medicament_composes');
    }
}
