<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Illuminate\Console\Command;
use Thettler\LaravelCommandAttributeSyntax\Attributes\ArtisanCommand;
use Thettler\LaravelCommandAttributeSyntax\Concerns\UsesAttributeSyntax;

#[ArtisanCommand(
    name: 'test:basic',
    description: 'Basic Command description!',
    help: 'Some Help.',
    hidden: true,
    aliases: ['alias:basic']
)]
class AttributeCommand extends Command
{
    use UsesAttributeSyntax;

    public function handle()
    {
    }
}
