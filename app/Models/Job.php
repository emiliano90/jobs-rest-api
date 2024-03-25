<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'salary',
		'country',
	];

	/**
	 * Get the skills that owns the job.
	 */
	public function skills()
	{
		return $this->belongsToMany(Skill::class, 'job_skill');
	}
}
