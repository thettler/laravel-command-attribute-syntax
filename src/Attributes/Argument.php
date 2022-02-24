<?php

namespace Thettler\LaravelCommandAttributeSyntax\Attributes;

use Carbon\Traits\Cast;
use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;
use Thettler\LaravelCommandAttributeSyntax\Contracts\ConsoleInput;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Argument implements ConsoleInput
{
    /**
     * @param  string  $description
     * @param  string|null  $as
     * @param  class-string<Caster>|null  $cast
     */
    public function __construct(
        protected string $description = '',
        protected ?string $as = null,
        protected null|string|Caster $cast = null,
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
}
