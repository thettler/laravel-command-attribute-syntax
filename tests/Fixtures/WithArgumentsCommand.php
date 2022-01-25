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
    #[Argument(optional: true)]
    protected string $optionalArgument;

    public function handle()
    {
        $this->line('Works!');
        $this->line($this->optionalArgument);

        return 1;
    }
}
