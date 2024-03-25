<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
	/**
	 * A job skill feature test.
	 */
	public function test_subscribe_job_notification(): void
	{
		$response = $this->post('/api/v1/subscribe', ['mail' => 'lorusso.emiliano@gmail.com', 'job_name' => 'Sr Php Developer', 'job_salary_min' => '15000', 'job_salary_max' => '30000', 'job_country' => 'Argentina']);

		$response = $this->post('/api/v1/subscribe', ['mail' => 'lorusso.emiliano2@gmail.com', 'job_name' => 'Sr Php Developer', 'job_country' => 'Argentina']);

		$response->assertStatus(200);
	}
}
