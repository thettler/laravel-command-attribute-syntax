<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:argument:array:optional',
)]
class WithOptionalArrayArgumentCommand extends Command
{
    #[Argument]
    protected ?array $arrayArgument;

    public function handle()
    {
        $this->line('Empty: '. implode(', ', $this->arrayArgument));
        return 1;
    }
}
