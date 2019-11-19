<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPharmaciesTable extends Migration
{
    public function up()
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->unsignedInteger('client_id');

            $table->foreign('client_id', 'client_fk_624268')->references('id')->on('clients');
        });
    }
}
