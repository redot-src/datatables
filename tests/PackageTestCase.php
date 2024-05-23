<?php

namespace Redot\Datatables\Tests;

use Orchestra\Testbench\TestCase;
use Redot\Datatables\DatatablesServiceProvider;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DatatablesServiceProvider::class,
        ];
    }
}
