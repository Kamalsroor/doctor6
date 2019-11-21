<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePharmaciesTable
 */
class CreatePharmaciesTable extends Migration
{
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phone');

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
