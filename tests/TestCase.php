<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests;

use Illuminate\Console\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Thettler\LaravelCommandAttributeSyntax\LaravelCommandAttributeSyntaxServiceProvider;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\BasicCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\WithArgumentsCommand;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        Application::starting(function ($artisan) {
            $artisan->add(app(BasicCommand::class));
            $artisan->add(app(WithArgumentsCommand::class));
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCommandAttributeSyntaxServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
