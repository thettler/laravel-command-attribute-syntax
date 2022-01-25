<?php

namespace Thettler\LaravelCommandAttributeSyntax\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Argument
{
    public function __construct(
        public readonly string $description = '',
        public readonly bool $optional = false,
    ) {
    }
}
