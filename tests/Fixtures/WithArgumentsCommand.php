<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:argument',
)]
class WithArgumentsCommand extends Command
{
    #[Argument]
    protected string $requiredArgument;

    #[Argument]
    protected ?string $optionalArgument;

    #[Argument]
    protected string $defaultArgument = 'default';

    public function handle()
    {
        $this->line('with argument().');

        $this->table(
            ['(requiredArgument)', '(optionalArgument)', '(defaultArgument)'],
            [[$this->argument('requiredArgument'), $this->argument('optionalArgument'), $this->argument('defaultArgument')]],
        );

        $this->line('with attributes.');
        $this->table(
            ['->requiredArgument', '->optionalArgument', '->defaultArgument'],
            [[$this->requiredArgument, $this->optionalArgument, $this->defaultArgument]],
        );

        return 1;
    }
}
