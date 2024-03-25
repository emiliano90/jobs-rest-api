<?php

namespace App\DataSources;

// Implement a concrete class for the external data source

use App\Contracts\JobDataSource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalJobDataSource implements JobDataSource
{
	public function getJobs(Request $request): LengthAwarePaginator
	{
		//Fetch jobs
		$externalJobs = $this->fetchExternalJobs();
		// Transform the external data if necessary
		$externalJobs = $this->modifyExternalJobs($externalJobs);
		//Paginate jobs and return
		return new LengthAwarePaginator($externalJobs->all(), $externalJobs->count(), $perPage = 10);
	}

	private function fetchExternalJobs(): Collection
	{
		try {
			//Fetch data from external_jobs_url
			$response = Http::get(config('services.external_jobs_url'));
			//Convert to collection and return
			return $response->collect();
		} catch (Exception $e) {

			//Handle external HTTP request error
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
