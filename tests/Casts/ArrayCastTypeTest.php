<?php

use Illuminate\Console\Command;
use Pest\Expectation;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;
use Thettler\LaravelCommandAttributeSyntax\Casts\ArrayCaster;
use Thettler\LaravelCommandAttributeSyntax\Casts\EnumCaster;
use Thettler\LaravelCommandAttributeSyntax\Concerns\UsesAttributeSyntax;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\Enum;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\IntEnum;
use Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums\StringEnum;

it('can cast array values', function () {
    $command = new class extends Command {
        use UsesAttributeSyntax;

        protected $name = 'test';

        #[Argument(
            cast: new ArrayCaster(new EnumCaster(Enum::class))
        )]
        public array $typedArray;

        #[Option(
            cast: new ArrayCaster(new EnumCaster(Enum::class))
        )]
        public array $typedArrayOption;

        public function handle()
        {
        }
    };

    $command = $this->callCommand($command, [
        'typedArray' => ['C', 'B'],
        '--typedArrayOption' => ['A', 'C'],
    ]);
    expect($command->typedArray)
        ->toBeArray()
        ->sequence(
            fn(Expectation $enum) => $enum->toEqual(Enum::C),
            fn(Expectation $enum) => $enum->toEqual(Enum::B)
        );

    expect($command->typedArrayOption)
        ->toBeArray()
        ->sequence(
            fn(Expectation $enum) => $enum->toEqual(Enum::A),
            fn(Expectation $enum) => $enum->toEqual(Enum::C)
        );
});

it('can cast default array values', function () {
    $command = new class extends Command {
        use UsesAttributeSyntax;

        protected $name = 'test';

        #[Argument(
            cast: new ArrayCaster(
                caster: new EnumCaster(),
                type: Enum::class
            )
        )]
        public array $typedDefaultArray = [Enum::A, Enum::C];

        #[Option(
            cast: new ArrayCaster(
                caster: new EnumCaster(),
                type: Enum::class
            )
        )]
        public array $typedDefaultArrayOption = [Enum::B, Enum::A];

        public function handle()
        {
        }
    };

    $command = $this->callCommand($command);

    expect($command->typedDefaultArray)
        ->toBeArray()
        ->sequence(
            fn(Expectation $enum) => $enum->toEqual(Enum::A),
            fn(Expectation $enum) => $enum->toEqual(Enum::C)
        );

    expect($command->typedDefaultArrayOption)
        ->toBeArray()
        ->sequence(
            fn(Expectation $enum) => $enum->toEqual(Enum::B),
            fn(Expectation $enum) => $enum->toEqual(Enum::A)
        );
});
