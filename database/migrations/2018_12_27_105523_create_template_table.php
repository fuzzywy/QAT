<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template', function (Blueprint $table) {
            $table->increments('id');
            $table->text('templateName');
            $table->text('elementId')->nullable();
            $table->text('description')->nullable();
            $table->string('user', 255);
            $table->string('format', 10)->nullable();
            // $table->timestamps();
            // $table->engine = 'InnoDB'; 
            // $table->charset = 'utf8';
            // $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template');
    }
}
