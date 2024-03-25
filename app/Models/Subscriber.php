<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
	use HasFactory;

	protected $fillable = [
		'mail',
		'job_name',
		'job_salary_min',
		'job_salary_max',
		'job_country',
	];
}
