<?php

namespace App\Http\Controllers;

use App\Contracts\FactoryJobServiceInterface;
use App\DataSources\EstructuraJson;
use App\DataSources\InternalJobDataSource;
use App\DataSources\ExternalJobDataSource;
use App\DataSources\InfoJobDataSource;
use App\Decorator\DecoratorJobExternal;
use App\Decorator\DecoratorJobInternal;
use App\Events\NewJobCreated;
use App\Models\Job;
use App\Http\Resources\JobResource;
use App\Http\Resources\MergedJobCollection;
use App\Services\JobService;
use App\Services\JobServiceConc;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class JobController extends Controller
{

	protected JobService $jobService;
	protected FactoryJobServiceInterface $factoryJobService;

	public function __construct(JobService $jobService, FactoryJobServiceInterface $factoryJobService)
	{
		$this->jobService = $jobService; //this is for use proxy pattern
		$this->factoryJobService = $factoryJobService; //this is for use decorator pattern
	}


	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		/*
		//Using decorator pattern
		if ($request->has('external_src') && $request->input('external_src') === "true")
		{
			Log::info("external_src = true");
			return $this->factoryJobService->create(true)->getPaginatedJobs($request);
		}
		else{
			Log::info("external_src  = false");
			return $this->factoryJobService->create(false)->getPaginatedJobs($request);
		}
		*/
		if ($request->has('external_src') && $request->input('external_src') === "true")
			$this->jobService->addDataSource(new ExternalJobDataSource(config('services.external_jobs_url'), new EstructuraJson()));
		$this->jobService->addDataSource(new InternalJobDataSource());
		//using composite pattern
		return $this->jobService->getMergedJobs($request);
		
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		try {
			$request->validate([
				'name' => 'bail|required|string|max:255',
				'salary' => 'bail|required|numeric|min:0',
				'country' => 'bail|required|string|max:255'
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'error' => $e->getMessage(), // Validation error message
				'errors' => $e->errors(), // Validation error details
			], Response::HTTP_UNPROCESSABLE_ENTITY); // Error Code 422
		}

		$job = new Job();

		$job->name = $request->input('name');
		$job->salary = $request->input('salary');
		$job->country = $request->input('country');

		$job->save();

		Log::info("Job ID {$job->id} created successfully.");

		// fire event, notify subscribers
		event(new NewJobCreated($job));

		return (new JobResource($job))->response()->setStatusCode(Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Job $job)
	{
		return (new JobResource($job))->response();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Job $job)
	{
		try {
			$request->validate([
				'name' => 'bail|string|max:255',
				'salary' => 'bail|numeric|min:0',
				'country' => 'bail|string|max:255',
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'error' => $e->getMessage(), // Validation error message
				'errors' => $e->errors(), // Validation error details
			], Response::HTTP_UNPROCESSABLE_ENTITY); // Error Code 422
		}

		if ($request->input('name') != null)
			$job->name = $request->input('name');
		if ($request->input('salary') != null)
			$job->salary = $request->input('salary');
		if ($request->input('country') != null)
			$job->country = $request->input('country');

		$job->save();


		Log::info("Job ID {$job->id} updated successfully.");

		// fire event, notify subscribers
		event(new NewJobCreated($job));

		return (new JobResource($job))->response();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Job $job)
	{
		$job->delete();

		Log::info("Job ID {$job->id} deleted successfully.");

		return response(null, Response::HTTP_NO_CONTENT);
	}
}
