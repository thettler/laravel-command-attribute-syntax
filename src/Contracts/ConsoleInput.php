<?php

namespace Thettler\LaravelCommandAttributeSyntax\Contracts;

interface ConsoleInput
{
    public function getDescription(): string;

    public function getAlias(): ?string;

    public function getCast(): null|Caster|string;
}
