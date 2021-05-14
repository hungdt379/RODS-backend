<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_information', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_id');
            $table->string('internal_meeting_id');
            $table->date('create_date');
            $table->timestamp('create_time');
            $table->string('dial_number');
            $table->boolean('running');
            $table->boolean('has_user_joined');
            $table->boolean('recording');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('participant_count');
            $table->integer('listener_count');
            $table->integer('max_users');
            $table->integer('moderator_count');
            $table->bigInteger('crawl_turn');
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
        Schema::dropIfExists('class_information');
    }
}
