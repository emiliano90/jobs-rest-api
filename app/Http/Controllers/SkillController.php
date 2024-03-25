<?php

namespace App\Http\Controllers;

use App\Http\Resources\SkillCollection;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SkillController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$skills = Skill::paginate();
		return (new SkillCollection($skills))->response();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		try {
			$request->validate([
				'name' => 'bail|required|string|max:255'
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'error' => $e->getMessage(), // Validation error message
				'errors' => $e->errors(), // Validation error details
			], Response::HTTP_UNPROCESSABLE_ENTITY); // Error Code 422
		}

		$skill = new Skill();

		$skill->name = $request->input('name');

		$skill->save();

		Log::info("Skill ID {$skill->id} created successfully.");

		return (new SkillResource($skill))->response()->setStatusCode(Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Skill $skill)
	{
		return (new SkillResource($skill))->response();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Skill $skill)
	{
		$request->validate([
			'name' => 'bail|string|required',
		]);

		$skill->name = $request->input('name');

		$skill->save();


		Log::info("Skill ID {$skill->id} updated successfully.");

		return (new SkillResource($skill))->response();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Skill $skill)
	{
		$skill->delete();

		Log::info("Skill ID {$skill->id} deleted successfully.");

		return response(null, Response::HTTP_NO_CONTENT);
	}
}
