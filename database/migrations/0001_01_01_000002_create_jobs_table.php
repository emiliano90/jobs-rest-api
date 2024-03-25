<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('jobs', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->integer('salary');
			$table->string('country');
			$table->timestamps();
		});

		Schema::create('job_skill', function (Blueprint $table) {
			$table->foreignId('job_id')->constrained();
			$table->foreignId('skill_id')->constrained();
			$table->timestamps();
			$table->unique(['job_id', 'skill_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('jobs');
		Schema::dropIfExists('job_skill');
	}
};
