<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Enums\ConsoleInputType;

class ArgumentReflection extends InputReflection
{
    public static function isArgument(\ReflectionProperty $property): bool
    {
        return ! empty($property->getAttributes(Argument::class));
    }

    public static function inputType(): ConsoleInputType
    {
        return ConsoleInputType::Argument;
    }
}
