<?php

namespace App\Services;

// Create a service to coordinate fetching data from multiple sources

use App\Contracts\JobDataSource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JobService
{
	//used for pagination
	const PERPAGE = 10;

	protected $internalDataSource;
	protected $externalDataSource;

	public function __construct(JobDataSource $internalDataSource, JobDataSource $externalDataSource)
	{
		$this->internalDataSource = $internalDataSource;
		$this->externalDataSource = $externalDataSource;
	}

	public function getMergedJobs(Request $request)
	{

		$internalJobs = $this->internalDataSource->getJobs($request);
		$externalJobs = $this->externalDataSource->getJobs($request);

		// Merge and paginate jobs
		$mergedJobs = $this->mergeJobs($internalJobs->items(), $externalJobs->items());
		$total = $internalJobs->total() + $externalJobs->total();

		$page = LengthAwarePaginator::resolveCurrentPage(); // Obtener el número de página actual

		return new LengthAwarePaginator($mergedJobs, $total, self::PERPAGE, $page);
	}

	protected function mergeJobs($internalJobs, $externalJobs)
	{
		//Job merge logic
		return array_merge($internalJobs, $externalJobs);
	}
}
