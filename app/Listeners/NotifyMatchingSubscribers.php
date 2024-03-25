<?php

namespace App\Listeners;

use App\Events\NewJobCreated;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewJobNotification;

class NotifyMatchingSubscribers implements ShouldQueue
{
	use InteractsWithQueue;

	/**
	 * Create the event listener.
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 */
	public function handle(NewJobCreated $event): void
	{
		$newJob = $event->job;

		// Get all subscribers
		$subscribers = Subscriber::all();

		foreach ($subscribers as $subscriber) {

			// Check if the job matches the subscriber's search criteria
			if ($this->jobMatchesCriteria($newJob, $subscriber)) {

				Log::info($subscriber->mail . " --> " . $newJob->name);
				// Send email notification to subscriber
				Mail::to($subscriber->mail)->send(new NewJobNotification($newJob));
			}
		}
	}

	// Implement logic to check if the job matches the subscriber's search criteria
	private function jobMatchesCriteria($job, $subscriber)
	{
		// Criteria comparison logic (e.g. salary, country, job name, etc.)
		return ($subscriber->job_salary_min == null || $job->salary >= $subscriber->job_salary_min) &&
			($subscriber->job_salary_max == null || $job->salary <= $subscriber->job_salary_max) &&
			($subscriber->job_country == null || $job->country == $subscriber->job_country) &&
			($subscriber->job_name == null || (stripos($job->name, $subscriber->job_name) !== false));
	}
}
