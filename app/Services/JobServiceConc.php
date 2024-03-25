<?php

namespace App\Services;

use App\Contracts\JobDataSourceDec;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class JobServiceConc implements JobDataSourceDec
{
	public function getJobs(Request $request): Collection
	{
		return new Collection([]);
	}
}
