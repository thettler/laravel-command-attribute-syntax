<?php

namespace Thettler\LaravelCommandAttributeSyntax\Contracts;

interface CastInterface
{
    public static function match(\ReflectionType $type, int|array|string|bool|null $value): bool;

    public function cast(mixed $value, \ReflectionNamedType $type): mixed;
}
