<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePartnersTable
 */
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

            $table->string('api_token', 60)->unique()->nullable()->default(null);

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
