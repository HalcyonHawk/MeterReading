<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeterReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id('meter_reading_id');
            $table->foreign('meter_id')->references('meter_id')->on('meters'); //Reading belongs to meter
            $table->foreign('user_id')->references('id')->on('users'); //Reading belongs to a user
            $table->int('reading');
            $table->date('date');
            $table->softDeletes(); //Soft delete to keep record
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_readings');
    }
}
