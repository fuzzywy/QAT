<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiformulaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpiformula', function (Blueprint $table) {
            $table->increments('id');
            $table->text('kpiName');
            $table->string('user', 255);
            $table->text('kpiFormula');
            $table->integer('kpiPrecision');
            $table->string('format', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpiformula');
    }
}
