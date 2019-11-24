<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWorktimesTable
 */
class CreateWorktimesTable extends Migration {

	public function up()
	{
		Schema::create('worktimes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->time('time');
            $table->string('status')->nullable();
			$table->integer('workday_id')->unsigned();
			$table->integer('client_id')->nullable()->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('worktimes');
	}
}
