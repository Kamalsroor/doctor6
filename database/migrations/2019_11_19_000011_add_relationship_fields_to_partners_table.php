<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPartnersTable extends Migration
{
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->unsignedInteger('specialty_id')->nullable();

            $table->foreign('specialty_id', 'specialty_fk_624198')->references('id')->on('specialties');
        });
    }
}
