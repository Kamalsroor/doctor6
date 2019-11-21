<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWorkdaysTable
 */
class CreateWorkdaysTable extends Migration {

	public function up()
	{
		Schema::create('workdays', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->date('day');
			$table->integer('partner_id')->unsigned();
			$table->time('from');
			$table->time('to');
			$table->integer('count')->nullable()->default('1');
		});
	}

	public function down()
	{
		Schema::drop('workdays');
	}
}
