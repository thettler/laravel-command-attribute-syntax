<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:basic',
    description: 'Basic Command description!',
    help: 'Some Help.',
)]
class BasicCommand extends Command
{
    public function handle()
    {
        $this->line('Works!');

        return 1;
    }
}
