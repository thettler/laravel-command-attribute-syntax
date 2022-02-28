<?php

namespace Thettler\LaravelCommandAttributeSyntax\Attributes;

use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;
use Thettler\LaravelCommandAttributeSyntax\Contracts\ConsoleInput;
use Thettler\LaravelCommandAttributeSyntax\Transfers\Validation;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Argument implements ConsoleInput
{
    /**
     * @param  string  $description
     * @param  string|null  $as
     * @param  class-string<Caster>|Caster|null  $cast
     * @param  string|array|null|Validation  $validation
     */
    public function __construct(
        protected string $description = '',
        protected ?string $as = null,
        protected null|string|Caster $cast = null,
        protected null|string|array|Validation $validation = null,
        protected ?bool $autoAsk = null,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAlias(): ?string
    {
        return $this->as;
    }

    public function getCast(): null|Caster|string
    {
        return $this->cast;
    }

    public function hasAutoAsk(): ?bool
    {
        return $this->autoAsk;
    }

    public function getValidation(): null|array|string|Validation
    {
        return $this->validation;
    }
}
