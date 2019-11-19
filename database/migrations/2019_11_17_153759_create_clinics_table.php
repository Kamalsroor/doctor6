<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClinicsTable extends Migration {

	public function up()
	{
		Schema::create('clinics', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('partner_id')->unsigned();
			$table->longText('address')->nullable();
			$table->integer('price')->nullable()->default('0');
			$table->string('long', 250)->nullable();
			$table->string('lat', 250)->nullable();
			$table->time('waiting_time')->nullable();
			$table->longText('info')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('clinics');
	}
}