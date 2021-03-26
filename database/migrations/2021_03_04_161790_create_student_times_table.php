<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_times', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('schedule_id');
            // $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreignId('time_id');
            $table->foreign('time_id')->references('id')->on('times');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_times');
    }
}
