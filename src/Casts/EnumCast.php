<?php

namespace Thettler\LaravelCommandAttributeSyntax\Casts;

use Thettler\LaravelCommandAttributeSyntax\Contracts\CastInterface;

class EnumCast implements CastInterface
{
     public static function match(\ReflectionType $type, int|array|string|bool|null $value): bool
    {
        if ( ! $type instanceof \ReflectionNamedType || $type->isBuiltin()) {
            return false;
        }

        return enum_exists($type->getName());
    }

    public function cast(mixed $value, \ReflectionNamedType $type): \UnitEnum
    {
        $enum = new \ReflectionEnum($type->getName());

        return $enum->isBacked()
            ? ($type->getName())::from((string) $value)
            : $enum->getCase((string) $value)->getValue();
    }
}
