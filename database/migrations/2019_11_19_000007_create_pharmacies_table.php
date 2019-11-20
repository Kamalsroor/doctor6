<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phone');

            $table->timestamps();

            $table->softDeletes();
            
            $table->unsignedInteger('client_id');

            $table->foreign('client_id', 'client_fk_624268')->references('id')->on('clients');
        });
    }
}
