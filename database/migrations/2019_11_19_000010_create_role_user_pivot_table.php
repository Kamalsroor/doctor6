<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRoleUserPivotTable
 */
class CreateRoleUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'user_id_fk_624000')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('role_id');

            $table->foreign('role_id', 'role_id_fk_624000')->references('id')->on('roles')->onDelete('cascade');
        });
    }
}
