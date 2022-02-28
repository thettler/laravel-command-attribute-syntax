<?php

namespace Thettler\LaravelCommandAttributeSyntax\Transfers;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\Input;
use Thettler\LaravelCommandAttributeSyntax\Enums\ConsoleInputType;
use Thettler\LaravelCommandAttributeSyntax\Reflections\InputReflection;

class InputErrorData
{
    public function __construct(
        public readonly string $key,
        public readonly array $choices,
        public readonly InputReflection $reflection,
        public readonly bool $hasAutoAsk,
    ) {
    }
}
