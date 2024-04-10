<?php

namespace App\Contracts;

//Contract for Proxy implementation

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface Estructura
{
	public function modifyJobs(Collection $external_jobs): Collection;
}
