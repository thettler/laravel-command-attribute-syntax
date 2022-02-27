<?php

namespace Thettler\LaravelCommandAttributeSyntax\Contracts;

use Thettler\LaravelCommandAttributeSyntax\Transfers\Validation;

interface ConsoleInput
{
    public function getDescription(): string;

    public function getAlias(): ?string;

    public function getCast(): null|Caster|string;

    public function getValidation(): null|array|string|Validation;
}
