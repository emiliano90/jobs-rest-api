<?php

namespace App\Decorator;

use App\Contracts\JobDataSourceDec;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DecoratorJob implements JobDataSourceDec
{
	//used for pagination
	const PERPAGE = 10;

	/**
	 * @var JobDataSourceDec
	 */
	protected $jobDataSourceDec;

	public function __construct(JobDataSourceDec $jobDataSourceDec)
	{
		$this->jobDataSourceDec = $jobDataSourceDec;
	}


	//The Decorator delegates all work to the wrapped component.
	public function getJobs(Request $request): Collection
	{
		return $this->jobDataSourceDec->getJobs($request);
	}

	//Return a paginated jobs
	public function getPaginatedJobs(Request $request): LengthAwarePaginator
	{
		return $this->paginateJobs($this->getJobs($request));
	}

	//Paginate a collection
	protected function paginateJobs(Collection $jobs): LengthAwarePaginator
	{
		// Get current page number
		$page = LengthAwarePaginator::resolveCurrentPage();
		// Get elements of current page
		$currentPageItems = $jobs->forPage($page, self::PERPAGE)->values();

		return new LengthAwarePaginator($currentPageItems, $jobs->count(), self::PERPAGE, $page);
	}

	//Logic for merge jobs
	protected function mergeJobs(Collection $jobs1, Collection $jobs2): Collection
	{
		return $jobs1->merge($jobs2);
	}
}
