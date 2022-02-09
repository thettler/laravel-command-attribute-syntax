<?php

namespace Thettler\LaravelCommandAttributeSyntax\Contracts;

interface CastInterface
{
    /**
     * This method returns true if the value can be cast by the cast() method
     *
     * @param  string  $typeName
     * @param  int|array|string|bool|null  $value
     * @return bool
     */
    public static function match(string $typeName, mixed $value): bool;

    /**
     * Casts a value to a different one and returns it. The returned value will be used to hydrate the
     * Argument or Option
     *
     * @param  mixed  $value
     * @param  string  $typeName
     * @return mixed
     */
    public function cast(mixed $value, string $typeName): mixed;
}
