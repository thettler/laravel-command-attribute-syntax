<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:hidden',
    hidden: true
)]
class BasicHiddenCommand extends Command
{
    public function handle()
    {
        $this->line('Is Hidden: ' . ($this->isHidden() ? 'Yes' : 'No'));
        return 1;
    }
}
