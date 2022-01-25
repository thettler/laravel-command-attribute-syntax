<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures;

use Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;
use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Attributes\Option;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'test:options',
)]
class WithOptionsCommand extends Command
{
    #[Option]
    protected bool $option;

    #[Option(
        shortcut: 'sc'
    )]
    protected bool $optionShortcut;

    #[Option(
        name: 'altName'
    )]
    protected bool $optionAltName;

    #[Option]
    protected string $optionWithValue;

    #[Option]
    protected ?string $optionWithNullableDefaultValue = 'Default';

    #[Option]
    protected string $optionWithDefaultValue = 'Default';

    #[Option]
    protected array $optionArray;


    public function handle()
    {

        $this->line('option: '.$this->option.', '.$this->option('option'));
        $this->line('optionShortcut: '.$this->optionShortcut.', '.$this->option('optionShortcut'));
        $this->line('optionAltName: ').$this->optionAltName.', '.$this->option('optionAltName');
        $this->line('optionWithValue: '.$this->optionWithValue.', '.$this->option('optionWithValue'));
        $this->line('optionWithNullableDefaultValue: '.$this->optionWithNullableDefaultValue.', '.$this->option('optionWithNullableDefaultValue'));
        $this->line('optionWithDefaultValue: '.$this->optionWithDefaultValue.', '.$this->option('optionWithDefaultValue'));
        $this->line('optionArray: '. implode(' ', $this->optionArray).', '.implode(' ',$this->option('optionArray')));

        return 1;
    }
}
