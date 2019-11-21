<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateMedicalSpecialtyTable
 */
class CreateMedicalSpecialtyTable extends Migration {

	public function up()
	{
		Schema::create('medical_specialty', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('medical_id')->unsigned();
			$table->integer('specialty_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('medical_specialty');
	}
}
