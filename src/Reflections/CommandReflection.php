<?php

namespace Thettler\LaravelCommandAttributeSyntax\Reflections;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Exceptions\CommandAttributeSyntaxException;

final class CommandReflection
{
    public readonly CommandAttribute $commandAttribute;

    protected function __construct(
        protected \ReflectionClass $reflection
    ) {
        $this->commandAttribute = $this->reflection
            ->getAttributes(CommandAttribute::class)[0]
            ->newInstance();
    }

    /**
     * @param  class-string<Command>  $command
     * @throws \ReflectionException
     * @throws CommandAttributeSyntaxException
     */
    public static function new(string $command): CommandReflection
    {
        if (!CommandReflection::usesAttributeSyntax($command)) {
            throw new CommandAttributeSyntaxException("$command does not uses Attribute Syntax.");
        }

        return new CommandReflection(new \ReflectionClass($command));
    }

    /**
     * @param  class-string<Command>  $command
     */
    public static function usesAttributeSyntax(string $command): bool
    {
        $reflection = new \ReflectionClass($command);
        $attributes = $reflection->getAttributes(CommandAttribute::class);

        if (empty($attributes)) {
            return false;
        }

        return true;
    }

    public function getArguments(): Collection
    {
        return collect($this->reflection->getProperties())
            ->filter(fn(\ReflectionProperty $property) => ArgumentReflection::isArgument($property))
            ->map(fn(\ReflectionProperty $property) => ArgumentReflection::new($property));
    }

    public function getOptions(): Collection
    {
        return collect($this->reflection->getProperties())
            ->filter(fn(\ReflectionProperty $property) => OptionReflection::isOption($property))
            ->map(fn(\ReflectionProperty $property) => OptionReflection::new($property));
    }

    public function getName(): string
    {
        return $this->commandAttribute->name;
    }

    public function getDescription(): string
    {
        return $this->commandAttribute->description;
    }

    public function getHelp(): string
    {
        return $this->commandAttribute->help;
    }

    public function isHidden(): bool
    {
        return $this->commandAttribute->hidden;
    }
}
