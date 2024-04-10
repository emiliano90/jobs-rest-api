<?php

namespace App\Decorator;


// Implement a concrete class for the external data source

use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DecoratorJobExternal extends DecoratorJob
{
	public function getJobs(Request $request): Collection
	{
		Log::info("getJobs ExternalJobDataSource");
		//Fetch jobs
		$externalJobs = $this->fetchExternalJobs($request);
		// Transform the external data if necessary
		$externalJobs = $this->modifyExternalJobs($externalJobs);
		//Merge external jobs with parent jobs and return data.
		return $this->mergeJobs(parent::getJobs($request), $externalJobs);
	}

	public function getPaginatedJobs(Request $request): LengthAwarePaginator
	{
		Log::info("DecoratorJobExternal getPaginatedJobs");
		return parent::getPaginatedJobs($request);
	}

	private function fetchExternalJobs(Request $request): Collection
	{
		try {
			//Fetch data from external_jobs_url
			$response = Http::get(config('services.external_jobs_url'), $request->query());
			//Convert to collection and return
			return $response->collect();
		} catch (Exception $e) {

			Log::error($e->getMessage());
			return collect(); // Return an empty collection
		}
	}

	//Modify external jobs adding key to collection
	//Aditionally we could remove undesired data -->to do
	private function modifyExternalJobs(Collection $external_jobs): Collection
	{
		return $external_jobs->map(function ($job) {
			return [
				'name' => $job[0],
				'salary' => $job[1],
				'country' => $job[2],
				'skills' => $job[3]
			];
		});
	}
}
