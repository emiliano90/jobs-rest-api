<?php
namespace App\Contracts;

use App\Contracts\JobDataSourceDec;

interface FactoryJobServiceInterface{
    public function create(bool $external_src) : JobDataSourceDec;

}