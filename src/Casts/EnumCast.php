<?php

namespace Thettler\LaravelCommandAttributeSyntax\Casts;

use Thettler\LaravelCommandAttributeSyntax\Contracts\CastInterface;

class EnumCast implements CastInterface
{
     public static function match(string $typeName, mixed $value): bool
    {
      return enum_exists($typeName);
    }

    public function cast(mixed $value, string $typeName): \UnitEnum
    {
        $enum = new \ReflectionEnum($typeName);

        return $enum->isBacked()
            ? ($typeName)::from((string) $value)
            : $enum->getCase((string) $value)->getValue();
    }
}
