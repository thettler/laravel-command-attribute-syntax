<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Illuminate\Console\Command;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;
use Thettler\LaravelCommandAttributeSyntax\Exceptions\CommandAttributeSyntaxException;

final class OptionReflection
{
    public readonly Option $optionAttribute;

    protected function __construct(
        protected \ReflectionProperty $property
    ) {
        $this->optionAttribute = $property->getAttributes(Option::class)[0]
            ->newInstance();
    }

    public static function new(\ReflectionProperty $property): static
    {
        if (!static::isOption($property)) {
            throw new CommandAttributeSyntaxException("$property->name has no Option Attribute.");
        }

        return new static($property);
    }

    public static function isOption(\ReflectionProperty $property): bool
    {
        return !empty($property->getAttributes(Option::class));
    }

    public function getName(): string
    {
        return $this->property->getName();
    }

    public function getAlternativeName(): ?string
    {
        return $this->optionAttribute->name;
    }

    public function getDescription(): string
    {
        return $this->optionAttribute->description;
    }

    public function getDefault(): string|bool|int|float|array|null
    {
        return $this->property->hasDefaultValue()
            ? $this->property->getDefaultValue()
            : null;
    }

    public function isOptional(): bool
    {
        return $this->property->hasDefaultValue() || $this->property->getType()?->allowsNull();
    }

    public function isNegatable(): bool
    {
        return $this->optionAttribute->negatable;
    }

    public function hasRequiredValue():bool
    {
        return $this->hasValue() && !$this->isOptional();
    }

    public function getShortcut(): ?string
    {
        return $this->optionAttribute->shortcut;
    }

    public function hasValue(): bool
    {
        if (($type = $this->property->getType()) instanceof \ReflectionNamedType){
            return $type->getName() !== 'bool';
        }

        return false;
    }

    public function isArray(): bool
    {
        if (($type = $this->property->getType()) instanceof \ReflectionNamedType){
            return $type->getName() === 'array';
        }

        return false;
    }
}
