<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobSkillTest extends TestCase
{
	/**
	 * A job skill feature test.
	 */
	public function test_post_add_skill_to_job(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$job_id = $response["data"]["id"];

		$response = $this->post('/api/v1/skills', ['name' => 'php']);
		$skill_id = $response["data"]["id"];

		$response = $this->post("/api/v1/jobs/{$job_id}/skills", ['skill' => $skill_id]);
		$response->assertStatus(201);
	}


	public function test_delete_skill_from_job(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$job_id = $response["data"]["id"];

		$response = $this->post('/api/v1/skills', ['name' => 'php']);
		$skill_id = $response["data"]["id"];

		$response = $this->post("/api/v1/jobs/{$job_id}/skills", ['skill' => $skill_id]);

		$response = $this->delete("/api/v1/jobs/{$job_id}/skills/{$skill_id}");

		$response->assertStatus(204);
	}

	public function test_get_skills(): void
	{
		$response = $this->post('/api/v1/jobs', ['name' => 'Sr Php Developer', 'salary' => '15000', 'country' => 'Argentina']);
		$job_id = $response["data"]["id"];

		$response = $this->post('/api/v1/skills', ['name' => 'php']);
		$skill_id = $response["data"]["id"];

		$response = $this->post("/api/v1/jobs/{$job_id}/skills", ['skill' => $skill_id]);

		$response = $this->get("/api/v1/jobs/{$job_id}/skills");

		$response->assertStatus(200);

		$response = $this->get("/api/v1/jobs/{$job_id}/skills/{$skill_id}");

		$response->assertStatus(200);
	}
}
