<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('clinics', function(Blueprint $table) {
			$table->foreign('partner_id')->references('id')->on('partners')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('partners', function(Blueprint $table) {
			$table->foreign('specialty_id')->references('id')->on('specialties')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('workdays', function(Blueprint $table) {
			$table->foreign('partner_id')->references('id')->on('partners')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('worktimes', function(Blueprint $table) {
			$table->foreign('workday_id')->references('id')->on('workdays')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('worktimes', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('medicals', function(Blueprint $table) {
			$table->foreign('partner_id')->references('id')->on('partners')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('medical_specialty', function(Blueprint $table) {
			$table->foreign('medical_id')->references('id')->on('medicals')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('medical_specialty', function(Blueprint $table) {
			$table->foreign('specialty_id')->references('id')->on('specialties')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('nurses', function(Blueprint $table) {
			$table->foreign('partner_id')->references('id')->on('partners')
						->onDelete('cascade')
						->onUpdate('no action');
		});
		Schema::table('pharmacies', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('cascade')
						->onUpdate('no action');
		});
	}

	public function down()
	{
		Schema::table('clinics', function(Blueprint $table) {
			$table->dropForeign('clinics_partner_id_foreign');
		});
		Schema::table('partners', function(Blueprint $table) {
			$table->dropForeign('partners_specialty_id_foreign');
		});
		Schema::table('workdays', function(Blueprint $table) {
			$table->dropForeign('workdays_partner_id_foreign');
		});
		Schema::table('worktimes', function(Blueprint $table) {
			$table->dropForeign('worktimes_workday_id_foreign');
		});
		Schema::table('worktimes', function(Blueprint $table) {
			$table->dropForeign('worktimes_client_id_foreign');
		});
		Schema::table('medicals', function(Blueprint $table) {
			$table->dropForeign('medicals_partner_id_foreign');
		});
		Schema::table('medical_specialty', function(Blueprint $table) {
			$table->dropForeign('medical_specialty_medical_id_foreign');
		});
		Schema::table('medical_specialty', function(Blueprint $table) {
			$table->dropForeign('medical_specialty_specialty_id_foreign');
		});
		Schema::table('nurses', function(Blueprint $table) {
			$table->dropForeign('nurses_partner_id_foreign');
		});
		Schema::table('pharmacies', function(Blueprint $table) {
			$table->dropForeign('pharmacies_client_id_foreign');
		});
	}
}