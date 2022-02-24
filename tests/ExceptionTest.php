<?php

use Illuminate\Console\Command;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Concerns\UsesAttributeSyntax;
use Thettler\LaravelCommandAttributeSyntax\Exception\InvalidTypeException;

it('Inputs need an type', function () {
    $command = new class () extends Command {
        use UsesAttributeSyntax;

        protected $name = 'test';

        #[Argument]
        public $requiredArgument;

        public function handle()
        {
        }
    };

    $this->callCommand($command, [
        'requiredArgument' => 'Argument_Required',
    ]);
})->throws(InvalidTypeException::class, 'A type is required for the console input "requiredArgument".');

it('Inputs only allows named types an type', function () {
    $command = new class () extends Command {
        use UsesAttributeSyntax;

        protected $name = 'test';

        #[Argument]
        public string|int $requiredArgument;

        public function handle()
        {
        }
    };

    $this->callCommand($command, [
        'requiredArgument' => 'Argument_Required',
    ]);
})->throws(InvalidTypeException::class, 'Only named types can be used for the console input "requiredArgument".');
