<?php

namespace App\Services;

// Create a service to coordinate fetching data from multiple sources

use App\Contracts\JobDataSource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JobService
{
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

		return new LengthAwarePaginator($mergedJobs, $total, $internalJobs->perPage(), $internalJobs->currentPage());
	}

	protected function mergeJobs($internalJobs, $externalJobs)
	{
		//Job merge logic
		return array_merge($internalJobs, $externalJobs);
	}
}
