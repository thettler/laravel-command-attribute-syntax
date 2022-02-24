<?php

namespace Thettler\LaravelCommandAttributeSyntax\Casts;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;

class ArrayCaster implements Caster
{
    /**
     * @param  Caster|class-string<Caster>  $caster
     */
    public function __construct(
        protected Caster|string|null $caster,
        protected string|null $type = 'array',
    ) {
    }

    public function from(mixed $value, string $type, \ReflectionProperty $property): int|float|array|string|bool|null
    {
        return Collection::wrap($value)
            ->map(fn($item) => $this->getItemCaster()->from($item, $this->type, $property))
            ->all();
    }

    public function to(mixed $value, string $type, \ReflectionProperty $property)
    {
        return Collection::wrap($value)
            ->map(fn($item) => $this->getItemCaster()->to($item, $this->type, $property))
            ->all();
    }

    protected function getItemCaster(): ?Caster
    {
        if (!$this->caster) {
            return null;
        }

        if (is_string($this->caster) && class_exists($this->caster)) {
            return app()->make($this->caster);
        }

        return $this->caster;
    }
}
