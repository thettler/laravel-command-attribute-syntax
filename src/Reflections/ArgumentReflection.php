<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

class ArgumentReflection extends InputReflection
{
    public static function isArgument(\ReflectionProperty $property): bool
    {
        return ! empty($property->getAttributes(Argument::class));
    }
}
