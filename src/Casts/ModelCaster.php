<?php

namespace Thettler\LaravelCommandAttributeSyntax\Casts;

use Thettler\LaravelCommandAttributeSyntax\Contracts\Caster;

class ModelCaster implements Caster
{

    public function __construct(
        protected ?string $findBy = null,
        protected array $select = ['*'],
        protected array $with = []
    ) {
    }

    public function from(mixed $value, string $type, \ReflectionProperty $property): int|float|array|string|bool|null
    {
        return $this->findBy ? $value->{$this->findBy} : $value->getKey();
    }

    public function to(mixed $value, string $type, \ReflectionProperty $property)
    {
        if (!$property->getType() instanceof \ReflectionNamedType) {
            return $value;
        }

        $modelName = $property->getType()->getName();

        return $modelName::where($this->findBy ?? (new $modelName())->getKeyName(), '=', $value)
            ->with($this->with)
            ->first($this->select);
    }

}
