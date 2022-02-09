<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCommandAttributeSyntaxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-command-attribute-syntax')
        ->hasConfigFile();
    }
}
