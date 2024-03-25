<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SkillTest extends TestCase
{
	/**
	 * A skill feature test.
	 */
	public function test_post_new_skill(): void
	{
		$response = $this->post('/api/v1/skills', ['name' => 'php']);
		$response->assertStatus(201);
	}

	public function test_update_skill(): void
	{
		$response = $this->post('/api/v1/skills', ['name' => 'php']);
		$id = $response["data"]["id"];

		$response = $this->put("/api/v1/skills/{$id}", ['name' => 'c++']);
		$response->assertStatus(200);
	}

	public function test_delete_skill(): void
	{
		$response = $this->post('/api/v1/skills', ['name' => 'laravel']);
		$id = $response["data"]["id"];

		$response = $this->delete("/api/v1/skills/{$id}");
		$response->assertStatus(204);
	}

	public function test_get_skills(): void
	{
		$response = $this->get('/api/v1/skills');

		$response->assertStatus(200);
		$response->assertJsonStructure([
			'data' => [
				'*' => [
					'id',
					'name',
				]
			]
		]);
	}
}
