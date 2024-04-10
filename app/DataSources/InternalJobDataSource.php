<?php

namespace App\DataSources;

// Implement a concrete class for the internal data source

use App\Contracts\JobDataSource;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InternalJobDataSource implements JobDataSource
{
	public function getJobs(Request $request): Collection
	{
		Log::info("getJobs InternalJobDataSource");
		//Add filters
		$query = $this->applyFilters(Job::query(), $request);
		//Get data paginated
		return $query->get();
	}

	//Create query based on the request
	private function applyFilters(Builder $query, Request $request)
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
