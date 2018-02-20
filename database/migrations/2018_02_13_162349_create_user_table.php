<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->string('lastname',50)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birthday')->nullable();
            $table->string('phone',45)->nullable();
            $table->string('cellphone',45)->nullable();
            $table->tinyInteger('genre')->default(1);
            $table->string('image',80)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('api_token');
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
         Schema::drop('user');
    }
}