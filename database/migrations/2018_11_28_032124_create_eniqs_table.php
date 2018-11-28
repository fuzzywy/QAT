<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEniqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eniqs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('conn');
            $table->string('city');
            $table->string('host');
            $table->integer('port');
            $table->string('dbName');
            $table->string('userName');
            $table->string('password');
            $table->string('subNetworkTdd');
            $table->string('subNetworkFdd');
            $table->string('subNetworkNbiot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eniqs');
    }
}
