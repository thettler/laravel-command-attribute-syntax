<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;

/**
 * @phpstan-type CasterConfigKey class-string<Caster>
 * @phpstan-type CasterConfigValue class-string | callable(mixed, \ReflectionProperty): bool | array<class-string>
 */
class ConsoleToolkit
{
    /** @var array<CasterConfigKey, CasterConfigValue> */
    public static array $casts = [];

    /**
     * @param  CasterConfigKey  $caster
     * @param  CasterConfigValue $matches
     * @return void
     */
    public static function addCast(string $caster, array|string|callable $matches): void
    {
        static::$casts[$caster] = $matches;
    }


    /**
     * @param array<CasterConfigKey, CasterConfigValue>  $caster
     * @return void
     */
    public static function setCast(array $caster): void
    {
        static::$casts = $caster;
    }
}
