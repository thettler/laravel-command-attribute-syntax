<?php

namespace Thettler\LaravelCommandAttributeSyntax\Contracts;

interface CastInterface
{
    public static function match(string $typeName, int|array|string|bool|null $value): bool;
    public function cast(mixed $value, string $typeName): mixed;
}
