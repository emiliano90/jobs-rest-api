<?php

namespace App\Listeners;

use App\Events\NewJobCreated;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use NewJobNotification;

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
				// Send email notification to subscriber
				Mail::to($subscriber->mail)->send(new NewJobNotification($newJob));
			}
		}
	}

	// Implementa la lógica para verificar si el trabajo coincide con los criterios de búsqueda del suscriptor
	private function jobMatchesCriteria($job, $subscriber)
	{
		// Criteria comparison logic (e.g. salary, country, job name, etc.)
		return $job->salary >= $subscriber->job_salary_min &&
			$job->salary <= $subscriber->job_salary_max &&
			$job->country == $subscriber->job_country &&
			stripos($job->name, $subscriber->job_name) !== false;
	}
}
