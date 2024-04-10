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

class EstructuraJson implements Estructura
{
	private ?array $estructura;

	function __construct(?array $estructura = null){
		$this->estructura = $estructura;
	}

	//Modify external jobs adding key to collection
	//Aditionally we could remove undesired data -->to do
	public function modifyJobs(Collection $external_jobs): Collection
	{
		return $external_jobs->map(function ($job) {
			if($this->estructura != null){
				return [
					'name' => $job[$this->estructura["name"]],
					'salary' => $job[$this->estructura["salary"]],
					'country' => $job[$this->estructura["country"]],
					'skills' => $job[$this->estructura["skills"]]
				];
			}
			else
				return [
					'name' => $job[0],
					'salary' => $job[1],
					'country' => $job[2],
					'skills' => $job[3]
				];
		});
	}
}
