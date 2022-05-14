<?php

namespace Dealskoo\Deal\Tests;

use Dealskoo\Deal\Providers\DealServiceProvider;

abstract class TestCase extends \Dealskoo\Seller\Tests\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DealServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
