<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thettler\LaravelCommandAttributeSyntax\Casts\EnumCaster;
use Thettler\LaravelCommandAttributeSyntax\Casts\ModelCaster;
use function Pest\Laravel\instance;

class LaravelCommandAttributeSyntaxServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        ConsoleToolkit::addCast(EnumCaster::class, function (mixed $value, \ReflectionProperty $property): bool {
            if (!$property->getType() instanceof \ReflectionNamedType) {
                return false;
            }

            return enum_exists($property->getType()->getName());
        });

        ConsoleToolkit::addCast(ModelCaster::class, function (mixed $value, \ReflectionProperty $property): bool {
            if (!$property->getType() instanceof \ReflectionNamedType) {
                return false;
            }

            return is_subclass_of($property->getType()->getName(), Model::class);
        });

        $package->name('laravel-command-attribute-syntax');
    }
}
