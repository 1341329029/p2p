<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grows', function (Blueprint $table) {
          $table->increments('gid');
          $table->integer('uid');
          $table->integer('pid');
          $table->string('title',60);
          $table->integer('amount');
          $table->date('paytime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grows');
    }
}
