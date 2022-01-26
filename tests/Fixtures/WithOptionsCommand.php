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
        shortcut: 'S'
    )]
    protected bool $optionShortcut;

    #[Option(
        name: 'alternative'
    )]
    protected bool $optionAlternativeName;

    #[Option(
        negatable: true
    )]
    protected bool $optionNegatable;

    #[Option]
    protected string $optionWithValue;

    #[Option]
    protected ?string $optionWithNullableValue;

    #[Option]
    protected string $optionWithDefaultValue = 'default';

    #[Option]
    protected array $optionArray;

    #[Option]
    protected array $optionDefaultArray = ['default1', 'default2'];

    public function handle()
    {
        $this->printOption('option');
        $this->printOption('optionShortcut');
        $this->printOption('optionAlternativeName', 'alternative');
        $this->printOption('optionNegatable', 'optionNegatable');

        if ($this->option('optionWithValue')) {
            $this->line('optionWithValue: '.$this->optionWithValue.', '.$this->option('optionWithValue'));
        }

        $this->line('optionWithNullableValue: '.$this->optionWithNullableValue.', '.$this->option('optionWithNullableValue'));
        $this->line('optionWithDefaultValue: '.$this->optionWithDefaultValue.', '.$this->option('optionWithDefaultValue'));

        $this->line('optionArray: '.implode(' ', $this->optionArray).', '.implode(' ', $this->option('optionArray')));
        $this->line('optionDefaultArray: '.implode(' ', $this->optionDefaultArray).', '.implode(' ', $this->option('optionDefaultArray')));

        return 1;
    }

    protected function printOption(string $name, ?string $alternativeName = null): void
    {
        $this->line($name.': '.$this->boolToString($this->{$name}).', '.$this->boolToString($this->option($alternativeName ?? $name)));
    }

    protected function boolToString(
        bool $bool
    ): string {
        return $bool ? 'true' : 'false';
    }
}
