<?php

namespace App\DataSources;

// Implement a concrete class for the external data source

use App\Contracts\Estructura;
use App\Contracts\JobDataSource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalJobDataSource implements JobDataSource
{
	private string $url;
	private Estructura $estructura;
	public function __construct(string $url, Estructura $estructura = null)
	{
		$this->url = $url;
		$this->estructura = $estructura;
	}
	public function getJobs(Request $request): Collection
	{
		Log::info("getJobs ExternalJobDataSource");
		//Fetch jobs
		$externalJobs = $this->fetchExternalJobs();
		// Transform the external data if necessary
		return $this->estructura->modifyJobs($externalJobs);
	}

	private function fetchExternalJobs(): Collection
	{
		try {
			//Fetch data from external_jobs_url
			$response = Http::get($this->url);
			//Convert to collection and return
			return $response->collect();
		} catch (Exception $e) {

			//Handle external HTTP request error
			Log::error($e->getMessage());
			return collect(); // Return an empty collection
		}
	}

}
