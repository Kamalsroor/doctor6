<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->string('phone');

            $table->string('username');

            $table->string('password');

            $table->string('type')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->unsignedInteger('specialty_id')->nullable();
            $table->foreign('specialty_id', 'specialty_fk_624198')->references('id')->on('specialties');
        });
    }
}
