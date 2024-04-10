<?php

namespace App\Decorator;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DecoratorJobInternal extends DecoratorJob
{
	public function getJobs(Request $request): Collection
	{
		Log::info("getJobs InternalJobDataSource");
		//Add filters
		$query = $this->applyFilters(Job::query(), $request);
		//Get data and convert to Collection
		$internalJobs = Collection::make($query->get());
		//Merge internalJobs with parent jobs
		return $this->mergeJobs(parent::getJobs($request), $internalJobs);
	}

	public function getPaginatedJobs(Request $request): LengthAwarePaginator
	{
		Log::info("DecoratorJobInternal getPaginatedJobs");
		return parent::getPaginatedJobs($request);
	}
	
	//Create query based on the request
	private function applyFilters(Builder $query, Request $request): Builder
	{
		if ($request->has('name')) {
			$query->where('name', 'like', '%' . $request->input('name') . '%');
		}
		if ($request->has('salary_min')) {
			$query->where('salary', '>', $request->input('salary_min'));
		}
		if ($request->has('salary_max')) {
			$query->where('salary', '<', $request->input('salary_max'));
		}
		if ($request->has('country')) {
			$query->where('country', 'like', '%' . $request->input('country') . '%');
		}
		if ($request->has(['field', 'sortOrder']) && $request->input('field') != null) {
			$query->orderBy($request->input('field'), $request->input('sortOrder'));
		}

		return $query;
	}
}
