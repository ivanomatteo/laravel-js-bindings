<?php

namespace IvanoMatteo\LaravelJsBindings\Tests;

use IvanoMatteo\LaravelJsBindings\FsUtils;
use Orchestra\Testbench\TestCase;
use IvanoMatteo\LaravelJsBindings\LaravelJsBindingsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelJsBindingsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {

        echo "\n";
        dump(FsUtils::listDir('.'));


        $this->assertTrue(true);
    }
}
