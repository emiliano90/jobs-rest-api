<?php

namespace App\Services;

use App\Contracts\JobDataSourceDec;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class JobServiceConc implements JobDataSourceDec
{
	public function getJobs(Request $request): Collection
	{
		Log::info("JobServiceConc getJobs");
		return new Collection([]);
	}
	public function getPaginatedJobs(Request $request): LengthAwarePaginator
	{
		Log::info("JobServiceConc getPaginatedJobs");
		return new LengthAwarePaginator([], 0, 0);
	}
}
