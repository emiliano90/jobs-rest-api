<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Assert;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobTest extends TestCase
{
	/**
	 * A job feature test.
	 */
	public function test_post_new_job(): void
	{
		$postData = ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina'];
		$response = $this->post('/api/v1/jobs', $postData);
		$response->assertStatus(201);
		$job = Job::find( $response["data"]["id"]);
		$this->assertNotNull($job);
		$this->assertEquals($job->name, $postData["name"]);
		$this->assertEquals($job->salary, $postData["salary"]);
		$this->assertEquals($job->country, $postData["country"]);
		
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
		$job = Job::find( $id);
		$this->assertNull($job);
	}

	public function test_get_jobs(): void
	{
		$url = '/api/v1/jobs';
		$start = true;
		$next_page = "";
		while($next_page != "" || $start)
		{
			$response = $this->get($url . $next_page);

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
			Log::info("next_page_url: " . $response["next_page_url"]);
			$next_page = $response["next_page_url"];
			$start = false;
		}
	}

	public function test_get_jobs_add_external_src(): void
	{
		$response = $this->get( '/api/v1/jobs?external_src=true');
		$response->assertStatus(200);
		$response->assertJsonStructure([
			'current_page',
			'data' => [
				'*' => [
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
