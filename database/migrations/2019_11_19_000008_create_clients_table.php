<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');

            $table->string('last_name');

            $table->string('email')->unique();

            $table->integer('age');

            $table->string('gender')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->longText('info');

            $table->string('password');

            $table->string('phone')->unique();

            $table->longText('address');

            $table->string('long')->nullable();

            $table->string('lat')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
