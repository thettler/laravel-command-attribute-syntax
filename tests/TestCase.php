<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests;

use Illuminate\Console\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Thettler\LaravelCommandAttributeSyntax\LaravelCommandAttributeSyntaxServiceProvider;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\BasicCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\BasicHiddenCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\WithArgumentsCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\WithArrayArgumentCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\WithDefaultArrayArgumentCommand;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\WithOptionalArrayArgumentCommand;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        Application::starting(function ($artisan) {
            $artisan->add(app(BasicCommand::class));
            $artisan->add(app(BasicHiddenCommand::class));
            $artisan->add(app(WithArgumentsCommand::class));
            $artisan->add(app(WithArrayArgumentCommand::class));
            $artisan->add(app(WithOptionalArrayArgumentCommand::class));
            $artisan->add(app(WithDefaultArrayArgumentCommand::class));
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
