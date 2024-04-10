<?php

namespace App\Contracts;

//Contract for Decorator implementation

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface JobDataSourceDec
{
	public function getJobs(Request $request): Collection;
	public function getPaginatedJobs(Request $request): LengthAwarePaginator;
}
