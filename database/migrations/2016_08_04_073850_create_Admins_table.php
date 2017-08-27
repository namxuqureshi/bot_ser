<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * BUTT: Where are other migrations ????????????????????????????
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('Admin', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('password');
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
        //
    }
}
