<?php

namespace App\Services;

// Create a service to coordinate fetching data from multiple sources

use App\Contracts\JobDataSource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use SplObjectStorage;

class JobService
{
	//used for pagination
	const PERPAGE = 10;

	protected SplObjectStorage $dataSources;
	
	public function __construct()
	{
		$this->dataSources = new SplObjectStorage ();
	}

	public function addDataSource(JobDataSource $dataSource)
	{
		$this->dataSources->attach($dataSource);
	}

	public function removeDataSource(JobDataSource $dataSource)
	{
		$this->dataSources->detach($dataSource);
	}

	public function getMergedJobs(Request $request) : LengthAwarePaginator
	{
		$mergedJobs = new Collection([]);
		foreach($this->dataSources as $dataSource){
			$jobs = $dataSource->getJobs($request);
			$mergedJobs = $this->mergeJobs($mergedJobs, $jobs);
		}
		$count = $this->dataSources->count();
		Log::info("DataSources Count: $count ");

		// Get current page number
		$page = LengthAwarePaginator::resolveCurrentPage();

		$currentPageItems = $mergedJobs->forPage($page, self::PERPAGE)->values();
		//Return paginated data
		return new LengthAwarePaginator($currentPageItems, $currentPageItems->count(), self::PERPAGE, $page);
	}

	protected function mergeJobs($jobs1, $jobs2)
	{
		//Job merge logic
		return $jobs1->merge($jobs2);
	}
}
