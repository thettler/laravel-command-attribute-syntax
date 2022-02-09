<?php

namespace Thettler\LaravelCommandAttributeSyntax;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thettler\LaravelCommandAttributeSyntax\Reflections\ArgumentReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\CommandReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\OptionReflection;

class Command extends \Illuminate\Console\Command
{
    protected CommandReflection $reflection;

    public function __construct()
    {
        if (! CommandReflection::usesAttributeSyntax($this::class)) {
            parent::__construct();

            return;
        }

        $this->reflection = CommandReflection::new($this::class);

        $this->configureUsingAttributeDefinition();
        $this->hydrateCommand();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->hydrateArguments();
        $this->hydrateOptions();

        return parent::execute($input, $output);
    }

    protected function hydrateCommand(): void
    {
        $this->setDescription($this->reflection->getDescription());
        $this->setHelp($this->reflection->getHelp());
        $this->setHidden($this->reflection->isHidden());
    }

    protected function hydrateArguments(): void
    {
        $this->reflection
            ->getArguments()
            ->each(function (ArgumentReflection $argumentReflection) {
                $this->{$argumentReflection->getName()} = $argumentReflection->cast($this->argument($argumentReflection->getName()));
            });
    }

    protected function hydrateOptions(): void
    {
        $this->reflection
            ->getOptions()
            ->each(function (OptionReflection $optionReflection) {
                $consoleName = $optionReflection->getAlternativeName() ?? $optionReflection->getName();

                if ($optionReflection->hasRequiredValue()) {
                    if ($this->option($consoleName) === null) {
                        return;
                    }

                    $this->{$optionReflection->getName()} = $optionReflection->cast($this->option($consoleName));

                    return;
                }

                $this->{$optionReflection->getName()} = $optionReflection->cast($this->option($consoleName));
            });
    }

    /**
     * Configure the console command using a fluent definition.
     */
    protected function configureUsingAttributeDefinition(): void
    {
        SymfonyCommand::__construct($this->name = $this->reflection->getName());

        $this->configureArgumentsUsingAttributeDefinition();
        $this->configureOptionsUsingAttributeDefinition();
    }

    protected function configureArgumentsUsingAttributeDefinition(): void
    {
        $this->reflection
            ->getArguments()
            ->each(function (ArgumentReflection $argumentReflection) {
                $this->getDefinition()
                    ->addArgument(
                        $this->propertyToArgument($argumentReflection)
                    );
            });
    }

    protected function configureOptionsUsingAttributeDefinition(): void
    {
        $this->reflection
            ->getOptions()
            ->each(function (OptionReflection $optionReflection) {
                $this->getDefinition()
                    ->addOption(
                        $this->propertyToOption($optionReflection)
                    );
            });
    }

    protected function propertyToArgument(ArgumentReflection $argument): InputArgument
    {
        switch (true) {
            case $argument->isArray() && ! $argument->isOptional():
                return $this->makeInputArgument($argument, InputArgument::IS_ARRAY | InputArgument::REQUIRED);

            case $argument->isArray():
                return $this->makeInputArgument($argument, InputArgument::IS_ARRAY, $argument->getDefault());

            case $argument->isOptional() || $argument->getDefault():
                return $this->makeInputArgument($argument, InputArgument::OPTIONAL, $argument->getDefault());

            default:
                return $this->makeInputArgument($argument, InputArgument::REQUIRED);
        }
    }

    protected function propertyToOption(OptionReflection $option): InputOption
    {
        switch (true) {
            case $option->hasValue() && $option->isArray():
                return $this->makeInputOption(
                    $option,
                    InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                    $option->getDefault()
                );

            case $option->hasValue() && ! $option->isOptional():
                return $this->makeInputOption($option, InputOption::VALUE_REQUIRED);

            case $option->hasValue():
                return $this->makeInputOption($option, InputOption::VALUE_OPTIONAL, $option->getDefault());

            case $option->isNegatable():
                return $this->makeInputOption(
                    $option,
                    InputOption::VALUE_NEGATABLE,
                    $option->getDefault() !== null ? $option->getDefault() : false
                );

            default:
                return $this->makeInputOption($option, InputOption::VALUE_NONE);
        }
    }

    protected function makeInputArgument(
        ArgumentReflection $argument,
        int $mode,
        string|bool|int|float|array|null $default = null
    ): InputArgument {
        return new InputArgument(
            $argument->getName(),
            $mode,
            $argument->getDescription(),
            $default
        );
    }

    protected function makeInputOption(
        OptionReflection $option,
        int $mode,
        string|bool|int|float|array|null $default = null
    ): InputOption {
        return new InputOption(
            $option->getAlternativeName() ?? $option->getName(),
            $option->getShortcut(),
            $mode,
            $option->getDescription(),
            $default
        );
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return parent::setDescription($description);
    }

    public function setHelp(string $help): static
    {
        $this->help = $help;

        return parent::setHelp($help);
    }
}
