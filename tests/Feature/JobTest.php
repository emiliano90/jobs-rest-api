<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobTest extends TestCase
{
	/**
	 * A job feature test.
	 */
	public function test_post_new_job(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$response->assertStatus(201);

		$response = $this->post('api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => -10, 'country' => 'Argentina']);
		$response->assertStatus(422);

		$response = $this->post('api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => 15000, 'country' => 345]);
		$response->assertStatus(422);

		$response = $this->post('api/v1/jobs', ['name' => 34534, 'salary' => 15000, 'country' => 'Argentina']);
		$response->assertStatus(422);
	}

	public function test_update_job(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$id = $response["data"]["id"];

		$response = $this->put("/api/v1/jobs/{$id}", ['name' => 'Sr Php Developer 2', 'salary' => -50, 'country' => 'Argentina']);
		$response->assertStatus(422);

		$response = $this->put("/api/v1/jobs/{$id}", ['name' => 'Sr Php Developer 2', 'salary' => '30000', 'country' => 'Argentina']);
		$response->assertStatus(200);
	}

	public function test_delete_job(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$id = $response["data"]["id"];

		$response = $this->delete("/api/v1/jobs/{$id}");
		$response->assertStatus(204);
	}

	public function test_get_jobs(): void
	{
		$response = $this->get('/api/v1/jobs');

		$response->assertStatus(200);

		$response->assertJsonStructure([
			'current_page',
			'data' => [
				'*' => [
					'id',
					'name',
					'salary',
					'country'
				]
			],
			"first_page_url",
			"from",
			"last_page",
			"last_page_url",
			"links" => [
				'*' => []
			],
			"next_page_url",
			"path",
			"per_page",
			"prev_page_url",
			"to",
			"total",
		]);
	}

	public function test_get_jobs_add_external_src(): void
	{
		$response = $this->get('/api/v1/jobs', ['external_src' => "true"]);

		$response->assertStatus(200);
		$response->assertJsonStructure([
			'current_page',
			'data' => [
				'*' => [
					'id',
					'name',
					'salary',
					'country'
				]
			],
			"first_page_url",
			"from",
			"last_page",
			"last_page_url",
			"links" => [
				'*' => []
			],
			"next_page_url",
			"path",
			"per_page",
			"prev_page_url",
			"to",
			"total",
		]);
	}
}
