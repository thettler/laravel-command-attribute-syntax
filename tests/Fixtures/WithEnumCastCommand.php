<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;
use Thettler\LaravelCommandAttributeSyntax\Command;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\Enum;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\IntEnum;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\StringEnum;

#[CommandAttribute(
    name: 'test:enum',
)]
class WithEnumCastCommand extends Command
{
    #[Argument]
    protected Enum $argEnum;

    #[Argument]
    protected StringEnum $argStringEnum;

    #[Argument]
    protected IntEnum $argIntEnum;

    #[Option]
    protected Enum $enum;

    #[Option]
    protected StringEnum $stringEnum;

    #[Option]
    protected IntEnum $intEnum;

    public function handle()
    {
        expect($this->argEnum)->toBeInstanceOf(Enum::class)->toBe(Enum::B);
        expect($this->argStringEnum)->toBeInstanceOf(StringEnum::class)->toBe(StringEnum::B);
        expect($this->argIntEnum)->toBeInstanceOf(IntEnum::class)->toBe(IntEnum::B);

        expect($this->enum)->toBeInstanceOf(Enum::class)->toBe(Enum::B);
        expect($this->stringEnum)->toBeInstanceOf(StringEnum::class)->toBe(StringEnum::B);
        expect($this->intEnum)->toBeInstanceOf(IntEnum::class)->toBe(IntEnum::B);

        return 1;
    }
}
