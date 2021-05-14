<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('architecture');
            $table->string('key_name');
            $table->string('instance_id');
            $table->string('instance_type');
            $table->string('instance_state');
            $table->string('subnet_id');
            $table->float('memory_size');
            $table->float('disk_size');
            $table->string('disk_type');
            $table->string('monitoring_state');
            $table->string('launch_time');
            $table->float('memory_usage');
            $table->float('disk_usage');
            $table->float('cpu_usage');
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
        Schema::dropIfExists('server_detail');
    }
}
