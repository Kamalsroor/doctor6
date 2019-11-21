<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateNursesTable
 */
class CreateNursesTable extends Migration {

	public function up()
	{
		Schema::create('nurses', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->longText('experience')->nullable();
			$table->integer('partner_id')->unsigned();
			$table->integer('age')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('nurses');
	}
}
