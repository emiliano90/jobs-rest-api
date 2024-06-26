<?php

namespace App\Contracts;

//Contract for Proxy implementation

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface JobDataSource
{
	public function getJobs(Request $request): Collection;
}
