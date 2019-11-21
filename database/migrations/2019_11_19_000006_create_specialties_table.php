<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSpecialtiesTable
 */
class CreateSpecialtiesTable extends Migration
{
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
