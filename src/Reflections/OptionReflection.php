<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Illuminate\Console\Command;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;

class OptionReflection extends InputReflection
{
    public function __construct(\ReflectionProperty $property, Option $consoleInput, Command $commandReflection)
    {
        parent::__construct($property, $consoleInput, $commandReflection);
    }

    public static function isOption(\ReflectionProperty $property): bool
    {
        return ! empty($property->getAttributes(Option::class));
    }

    public function isNegatable(): bool
    {
        return $this->consoleInput->isNegatable();
    }

    public function hasRequiredValue(): bool
    {
        return $this->hasValue() && ! $this->isOptional();
    }

    public function getShortcut(): ?string
    {
        return $this->consoleInput->getShortcut();
    }

    public function hasValue(): bool
    {
        if (($type = $this->property->getType()) instanceof \ReflectionNamedType) {
            return $type->getName() !== 'bool';
        }

        return false;
    }
}
