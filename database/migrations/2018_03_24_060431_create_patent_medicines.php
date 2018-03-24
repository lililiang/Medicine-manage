<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatentMedicines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('patent_medicines', function (Blueprint $table) {
            $table->increments('mpm_id');
            $table->string('patent_name');
            $table->string('composes');
            $table->text('function');
            $table->text('treatment');
            $table->string('usage');
            $table->string('dosage');
            $table->text('reactions');
            $table->string('forbbiden');
            $table->text('warnings');
            $table->string('intereact');
            $table->text('pharmacologic');
            $table->string('storage_method');
            $table->string('terms');
            $table->string('license_number');
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
        Schema::dropIfExists('patent_medicines');
    }
}
