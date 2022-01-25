<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:basic',
    description: 'Basic Command description!',
    help: 'Some Help.',
    hidden: false
)]
class BasicCommand extends Command
{
    public function handle()
    {
        $this->line('Works!');
        $this->table(
            ['Description', 'Help', 'Hidden'],
            [[$this->description, $this->help, $this->isHidden()]]
        );
        return 1;
    }
}
