<?php

namespace Thettler\LaravelCommandAttributeSyntax\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Option
{
    public function __construct(
        public readonly string $description = '',
        public readonly ?string $name = '',
        public readonly ?string $shortcut = null,
    ) {
    }
}
