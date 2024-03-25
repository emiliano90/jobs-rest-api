<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class JobController extends Controller
{

	protected $jobService;
	protected $jobServiceConc;

	public function __construct(JobService $jobService, JobServiceConc $jobServiceConc)
	{
		$this->jobService = $jobService; //this is for use proxy pattern
		$this->jobServiceConc = $jobServiceConc; //this is for use decorator pattern
	}


	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		//Using decorator pattern
		$this->jobServiceConc = new DecoratorJobInternal($this->jobServiceConc);
		if ($request->has('external_src') && $request->input('external_src') === "true")
			$this->jobServiceConc = new DecoratorJobExternal($this->jobServiceConc);

		return $this->jobServiceConc->getPaginatedJobs($request);

		//using proxy pattern
		//$mergedJobs = $this->jobService->getMergedJobs($request);
		//return (new MergedJobCollection($mergedJobs))->response();
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
