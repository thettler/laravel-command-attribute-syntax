<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:argument:array',
)]
class WithArrayArgumentCommand extends Command
{
    #[Argument]
    protected array $arrayArgument;

    public function handle()
    {
        $this->line(implode(', ', $this->arrayArgument));
        return 1;
    }
}
