<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicineIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('medicine_ingredients', function (Blueprint $table) {
            $table->increments('mmi_id');
            $table->string('factor_name');
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
