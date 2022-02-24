<?php

namespace Thettler\LaravelCommandAttributeSyntax\Concerns;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thettler\LaravelCommandAttributeSyntax\Reflections\ArgumentReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\CommandReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\OptionReflection;

trait UsesAttributeSyntax
{
    protected CommandReflection $reflection;

    public function __construct()
    {
        $this->configureDefaults();
        parent::__construct();
    }

    public function specifyParameters()
    {
        $this->reflection = new CommandReflection($this);

        if ($this->reflection->usesCommandAttribute()) {
            SymfonyCommand::__construct($this->name = $this->reflection->getName());
            $this->setDescription($this->reflection->getDescription());
            $this->setHelp($this->reflection->getHelp());
            $this->setHidden($this->reflection->isHidden());
            $this->setAliases($this->reflection->getAliases());
        }

        parent::specifyParameters();
    }

    public function configureDefaults(): void
    {

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        if (!$this->reflection->usesInputAttributes()) {
            return [];
        }

        return $this->reflection
            ->getArguments()
            ->map(fn(ArgumentReflection $argumentReflection) => $this->propertyToArgument($argumentReflection))
            ->all();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        if (!$this->reflection->usesInputAttributes()) {
            return [];
        }

        return $this->reflection
            ->getOptions()
            ->map(fn(OptionReflection $optionReflection) => $this->propertyToOption($optionReflection))
            ->all();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->hydrateArguments();
        $this->hydrateOptions();

        return parent::execute($input, $output);
    }

    protected function hydrateArguments(): void
    {
        $this->reflection
            ->getArguments()
            ->each(function (ArgumentReflection $argumentReflection) {
                $this->{$argumentReflection->getName()} = $argumentReflection->castTo(
                    $this->argument($argumentReflection->getAlias() ?? $argumentReflection->getName())
                );
            });
    }

    protected function hydrateOptions(): void
    {
        $this->reflection
            ->getOptions()
            ->each(function (OptionReflection $optionReflection) {
                $consoleName = $optionReflection->getAlias() ?? $optionReflection->getName();
                if (!$optionReflection->hasRequiredValue()) {
                    $this->{$optionReflection->getName()} = $optionReflection->castTo($this->option($consoleName));

                    return;
                }

                if ($this->option($consoleName) === null) {
                    return;
                }

                $this->{$optionReflection->getName()} = $optionReflection->castTo($this->option($consoleName));
            });
    }


    protected function propertyToArgument(ArgumentReflection $argument): InputArgument
    {
        return match (true) {
            $argument->isArray() && !$argument->isOptional() => $this->makeInputArgument($argument,
                InputArgument::IS_ARRAY | InputArgument::REQUIRED),

            $argument->isArray() => $this->makeInputArgument($argument, InputArgument::IS_ARRAY,
                $argument->getDefaultValue()),

            $argument->isOptional() || $argument->getDefaultValue() => $this->makeInputArgument($argument,
                InputArgument::OPTIONAL, $argument->getDefaultValue()),

            default => $this->makeInputArgument($argument, InputArgument::REQUIRED),
        };
    }

    protected function propertyToOption(OptionReflection $option): InputOption
    {
        return match (true) {
            $option->hasValue() && $option->isArray() => $this->makeInputOption(
                $option,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                $option->getDefaultValue()
            ),

            $option->hasValue() && !$option->isOptional() => $this->makeInputOption($option,
                InputOption::VALUE_REQUIRED),

            $option->hasValue() => $this->makeInputOption($option, InputOption::VALUE_OPTIONAL,
                $option->getDefaultValue()),

            $option->isNegatable() => $this->makeInputOption(
                $option,
                InputOption::VALUE_NEGATABLE,
                $option->getDefaultValue() !== null ? $option->getDefaultValue() : false
            ),

            default => $this->makeInputOption($option, InputOption::VALUE_NONE),
        };
    }

    protected function makeInputArgument(
        ArgumentReflection $argument,
        int $mode,
        string|bool|int|float|array|null $default = null
    ): InputArgument {
        return new InputArgument(
            $argument->getAlias() ?? $argument->getName(),
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
            $option->getAlias() ?? $option->getName(),
            $option->getShortcut(),
            $mode,
            $option->getDescription(),
            $default
        );
    }
}

