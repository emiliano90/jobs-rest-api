<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Http\Resources\SkillCollection;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class JobSkillController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Job $job)
	{
		return (new SkillCollection($job->skills))->response();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Job $job, Request $request)
	{
		try {
			$request->validate([
				'skill' => 'bail|required|exists:skills,id|unique:job_skill,skill_id,NULL,id,job_id,' . $job->id
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'error' => $e->getMessage(), // Validation error message
				'errors' => $e->errors(), // Validation error details
			], Response::HTTP_UNPROCESSABLE_ENTITY); // Error Code 422
		}

		$skill = Skill::findOrFail($request->input('skill'));
		$job->skills()->attach($skill);

		return (new JobResource($job))->response()->setStatusCode(Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Job $job, Skill $skill)
	{
		$skill = $job->skills()->findOrFail($skill->id);
		return (new SkillResource($skill))->response();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Job $job)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Job $job, Skill $skill)
	{
		$skill = $job->skills()->findOrFail($skill->id);
		$job->skills()->detach($skill->id);
		return response(null, Response::HTTP_NO_CONTENT);
	}
}
