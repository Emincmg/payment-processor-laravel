<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class UnitTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app){
        return ['Emincmg\PaymentProcessorLaravel\Providers\PaymentServiceProvider'];
    }
}