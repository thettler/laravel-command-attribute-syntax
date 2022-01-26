<?php

namespace Thettler\LaravelCommandAttributeSyntax\Attributes;

use Thettler\LaravelCommandAttributeSyntax\Exceptions\CommandAttributeSyntaxException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Option
{
    public function __construct(
        public readonly string $description = '',
        public readonly ?string $name = null,
        public readonly ?string $shortcut = null,
        public readonly bool $negatable = false,
    ) {
        throw_if(
            $this->shortcut !== null && strlen($this->shortcut) > 1,
            new CommandAttributeSyntaxException("Shortcuts for Options can only be one char long. Shortcut: $this->shortcut")
        );
    }
}
