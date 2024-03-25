<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
	];

	/**
	 * Get the jobs associated with the skill.
	 */
	public function jobs()
	{
		return $this->belongsToMany(Job::class, 'job_skill');
	}
}
