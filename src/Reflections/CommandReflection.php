<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\ArtisanCommand;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;

class CommandReflection
{
    public \ReflectionClass $reflection;
    public ?ArtisanCommand $attribute;

    public function __construct(
        public Command $command
    ) {
        $this->reflection = new \ReflectionClass($this->command);
        $this->attribute = $this->initCommandAttribute();
    }

    public function usesAttributeSyntax(): bool
    {
        return $this->usesCommandAttribute() || $this->usesInputAttributes();
    }

    public function usesCommandAttribute(): bool
    {
        return $this->attribute !== null;
    }

    public function usesInputAttributes(): bool
    {
        return $this->getArguments()->isNotEmpty() || $this->getOptions()->isNotEmpty();
    }

    public function getArguments(): Collection
    {
        return collect($this->reflection->getProperties())
            ->filter(fn (\ReflectionProperty $property) => ArgumentReflection::isArgument($property))
            ->map(fn (\ReflectionProperty $property) => new ArgumentReflection(
                $property,
                $property->getAttributes(Argument::class)[0]->newInstance(),
                $this->command,
            )
            );
    }

    public function getOptions(): Collection
    {

        return collect($this->reflection->getProperties())
            ->filter(fn (\ReflectionProperty $property) => OptionReflection::isOption($property))
            ->map(fn (\ReflectionProperty $property) => new OptionReflection(
                $property,
                $property->getAttributes(Option::class)[0]->newInstance(),
                $this->command,
            )
            );
    }

    public function getName(): ?string
    {
        if (! $this->usesCommandAttribute()) {
            return $this->command->getName();
        }

        return $this->attribute->name;
    }

    public function getDescription(): string
    {
        if (! $this->usesCommandAttribute()) {
            return $this->command->getDescription();
        }

        return $this->attribute->description;
    }

    public function getHelp(): string
    {
        if (! $this->usesCommandAttribute()) {
            return $this->command->getHelp();
        }

        return $this->attribute->help;
    }

    public function isHidden(): bool
    {
        if (! $this->usesCommandAttribute()) {
            return $this->command->isHidden();
        }

        return $this->attribute->hidden;
    }

    public function getAliases(): array
    {
        if (! $this->usesCommandAttribute()) {
            return [];
        }

        return $this->attribute->aliases;
    }

    protected function initCommandAttribute(): ?ArtisanCommand
    {
        $attributes = $this->reflection->getAttributes(ArtisanCommand::class);

        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
