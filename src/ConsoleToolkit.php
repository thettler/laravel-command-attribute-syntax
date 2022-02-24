<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;

class ConsoleToolkit
{
    /** @var array<class-string, class-string | callable(mixed, \ReflectionType): bool | array<class-string>> */
    public static array $casts = [];

    /**
     * @param  class-string<Caster>  $caster
     * @param  class-string|array<class-string>|callable(mixed, \ReflectionType): bool  $caster
     * @return void
     */
    public static function addCast(string $caster, array|string|callable $matches): void
    {

        static::$casts[$caster] = $matches;
    }


    /**
     * @param array<class-string, class-string | callable(mixed, \ReflectionType): bool | array<class-string>>  $caster
     * @return void
     */
    public static function setCast(array $caster): void
    {
        static::$casts = $caster;
    }
}
