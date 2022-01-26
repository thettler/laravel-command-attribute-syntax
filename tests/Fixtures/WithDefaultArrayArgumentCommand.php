<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:argument:array:default',
)]
class WithDefaultArrayArgumentCommand extends Command
{
    #[Argument]
    protected array $arrayArgument = ['Item 1', 'Item 2'];

    public function handle()
    {
        $this->line(implode(', ', $this->arrayArgument));

        return 1;
    }
}
