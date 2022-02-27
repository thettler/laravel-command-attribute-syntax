<?php

namespace Thettler\LaravelCommandAttributeSyntax\Concerns;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thettler\LaravelCommandAttributeSyntax\Exceptions\ValidationException;
use Thettler\LaravelCommandAttributeSyntax\Reflections\ArgumentReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\CommandReflection;
use Thettler\LaravelCommandAttributeSyntax\Reflections\OptionReflection;

trait UsesAttributeSyntax
{
    use UsesInputValidation;

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
        $hasErrors = !$this->errorHandling(fn() => $this->hydrateArguments());
        $hasErrors = !$this->errorHandling(fn() => $this->hydrateOptions()) || $hasErrors;
        if ($hasErrors) {
            return SymfonyCommand::FAILURE;
        }

        return parent::execute($input, $output);
    }

    protected function errorHandling(callable $callable): bool
    {
        try {
            $callable();
            return true;
        } catch (ValidationException $validationException) {

            foreach ($validationException->validator->errors()->toArray() as $key => $errors) {
                $this->line(" ");

                foreach ($errors as $error) {
                    $this->error($error);
                }


                if (!array_key_exists($key, $validationException->choices)){
                    continue;
                }

                $this->info("Possible values for: {$key}.");

                foreach ($validationException->choices[$key] as $choice) {
                    $this->warn("   - {$choice}");
                }

            }

            return false;
        }
    }

    protected function hydrateArguments(): void
    {
        $this->reflection
            ->getArguments()
            ->pipeThrough(fn(Collection $collection) => $this->validate($collection))
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
            ->pipeThrough(fn(Collection $collection) => $this->validate($collection))
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
            $argument->isArray() && !$argument->isOptional() => $this->makeInputArgument(
                $argument,
                InputArgument::IS_ARRAY | InputArgument::REQUIRED
            ),

            $argument->isArray() => $this->makeInputArgument(
                $argument,
                InputArgument::IS_ARRAY,
                $argument->getDefaultValue()
            ),

            $argument->isOptional() || $argument->getDefaultValue() => $this->makeInputArgument(
                $argument,
                InputArgument::OPTIONAL,
                $argument->getDefaultValue()
            ),

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

            $option->hasValue() && !$option->isOptional() => $this->makeInputOption(
                $option,
                InputOption::VALUE_REQUIRED
            ),

            $option->hasValue() => $this->makeInputOption(
                $option,
                InputOption::VALUE_OPTIONAL,
                $option->getDefaultValue()
            ),

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
