<?php

namespace Thettler\LaravelCommandAttributeSyntax\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Thettler\LaravelCommandAttributeSyntax\Enums\ConsoleInputType;
use Thettler\LaravelCommandAttributeSyntax\Exceptions\ValidationException;
use Thettler\LaravelCommandAttributeSyntax\Reflections\InputReflection;

trait UsesInputValidation
{
    /**
     * @param  Collection<InputReflection>  $collection
     * @return Collection<InputReflection>
     * @throws ValidationException
     */
    protected function validate(Collection $collection): Collection
    {
        [
            'values' => $values,
            'rules' => $rules,
            'messages' => $messages,
            'choices' => $choices
        ] = $this->extractValidationData($collection);
        if (empty($rules)) {
            return $collection;
        }

        $validator = Validator::make(
            $values,
            $rules,
            $messages
        );

        if (!$validator->fails()) {
            return $collection;
        }

        throw new ValidationException($validator, $choices);
    }


    /**
     * @return array{values:mixed, rules:null|string|array, messages: array}
     */
    protected function extractValidationData(Collection $collection): array
    {
        return $collection->reduce(fn(array $carry, InputReflection $reflection) => [
            'values' => [...$carry['values'], ...$this->extractInputValues($reflection)],
            'rules' => [...$carry['rules'], ...$this->extractInputRules($reflection)],
            'messages' => [...$carry['messages'], ...$this->extractValidationMessages($reflection)],
            'choices' => [...$carry['choices'], ...$this->extractInputChoices($reflection)],
        ], [
            'values' => [],
            'rules' => [],
            'messages' => [],
            'choices' => [],
        ]);
    }

    protected function extractValidationMessages(InputReflection $reflection): array
    {
        if (!$reflection->getValidationMessage()) {
            return [];
        }

        return collect($reflection->getValidationMessage())
            ->mapWithKeys(fn(string $value, string $key) => ["{$reflection->getName()}.{$key}" => $value])
            ->all();
    }


    protected function extractInputValues(
        InputReflection $reflection
    ): array {
        $inputName = $reflection->getAlias() ?? $reflection->getName();

        return [
            $reflection->getName() => match ($reflection::inputType()) {
                ConsoleInputType::Argument => $this->argument($inputName),
                ConsoleInputType::Option => $this->option($inputName),
            }
        ];
    }

    protected function extractInputRules(
        InputReflection $reflection
    ): array {
        if (empty($reflection->getValidationRules())) {
            return [];
        }

        return [$reflection->getName() => $reflection->getValidationRules()];
    }

    protected function extractInputChoices(
        InputReflection $reflection
    ): array {
        if (empty($reflection->getChoices())) {
            return [];
        }

        return [$reflection->getName() => $reflection->getChoices()];
    }
}
