<?php
namespace App\Factory;

use App\Contracts\FactoryJobServiceInterface;
use App\Contracts\JobDataSourceDec;
use App\Decorator\DecoratorJobExternal;
use App\Decorator\DecoratorJobInternal;
use App\Services\JobServiceConc;

class FactoryJobService implements FactoryJobServiceInterface
{

	public function create(bool $external_src = false) : JobDataSourceDec
	{
		$jobService = new JobServiceConc();
		$jobService = new DecoratorJobInternal($jobService);
		if($external_src)
			$jobService = new DecoratorJobExternal($jobService);
		return $jobService;
	}
}