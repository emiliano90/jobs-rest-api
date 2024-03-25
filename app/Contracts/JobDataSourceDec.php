<?php

namespace App\Contracts;

//Contract for Decorator implementation

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface JobDataSourceDec
{
	public function getJobs(Request $request): Collection;
}
