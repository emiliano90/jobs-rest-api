<?php

namespace App\Providers;

use App\Contracts\FactoryJobServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Contracts\JobDataSource;
use App\Contracts\JobDataSourceDec;
use App\DataSources\ExternalJobDataSource;
use App\DataSources\InternalJobDataSource;
use App\Factory\FactoryJobService;
use App\Services\JobService;
use App\Services\JobServiceConc;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{

		/*// Register InternalJobDataSource as JobDataSource
		$this->app->bind(JobDataSource::class, function ($app) {
			return new InternalJobDataSource();
		});

		// Register ExternalJobDataSource as JobDataSource
		$this->app->bind(JobDataSource::class, function ($app) {
			return new ExternalJobDataSource();
		});
*/
		// Register JobService with dependencis
		$this->app->bind(JobService::class, function ($app) {
			// Resolves JobDataSource instances
			//$internalDataSource = $app->make(InternalJobDataSource::class);
			//$externalDataSource = $app->make(ExternalJobDataSource::class);
			// Create a new JobService instance with dependencies resolved
			return new JobService();
		});

		// Register JobServiceConc as JobDataSourceDec
		$this->app->bind(FactoryJobServiceInterface::class, function ($app) {
			return new FactoryJobService();
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//
	}
}
