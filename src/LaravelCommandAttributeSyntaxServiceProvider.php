<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\BasicCommand;

class LaravelCommandAttributeSyntaxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-command-attribute-syntax')
            ->hasConfigFile()
            ->hasCommand(BasicCommand::class);
    }
}
