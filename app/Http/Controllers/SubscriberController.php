<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SubscriberController extends Controller
{
	public function subscribe(Request $request)
	{
		try {
			$request->validate([
				'mail' => 'required|email|unique:subscribers,mail',
				'job_name' => 'nullable|string',
				'job_salary_min' => 'nullable|numeric|min:0',
				'job_salary_max' => 'nullable|numeric|min:0',
				'job_country' => 'nullable|string',
			]);
		} catch (ValidationException $e) {
			return response()->json([
				'error' => $e->getMessage(), // Validation error message
				'errors' => $e->errors(), // Validation error details
			], Response::HTTP_UNPROCESSABLE_ENTITY); // Error Code 422
		}

		Subscriber::create($request->all());

		return response("You have successfully subscribed", Response::HTTP_OK);
	}
}
