<?php

namespace App\Contracts;

//Contract for Proxy implementation

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface JobDataSource
{
	public function getJobs(Request $request): LengthAwarePaginator;
}
