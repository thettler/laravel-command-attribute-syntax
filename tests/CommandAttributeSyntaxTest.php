<?php

use function Pest\Laravel\artisan;

it('will be registered correctly', function () {
    artisan('test:basic')
        ->expectsOutput('Works!');
});

it('will update description and help', function () {
    artisan('test:basic --help')
        ->expectsOutput('Description:')
        ->expectsOutput('  Basic Command description!')
        ->expectsOutput('Help:')
        ->expectsOutput('  Some Help.');
});

it('will hide hidden command', function () {
    artisan('test:hidden')
        ->expectsOutput('Is Hidden: Yes');
});

it('can use Arguments', function () {
    artisan('test:argument Required Optional DefaultValue')
        ->expectsTable(
            ['(requiredArgument)', '(optionalArgument)', '(defaultArgument)'],
            [['Required', 'Optional', 'DefaultValue']],
        )
        ->expectsTable(
            ['->requiredArgument', '->optionalArgument', '->defaultArgument'],
            [['Required', 'Optional', 'DefaultValue']],
        );
});

it('can use optional Arguments', function () {
    artisan('test:argument Required')
        ->expectsTable(
            ['(requiredArgument)', '(optionalArgument)', '(defaultArgument)'],
            [['Required', '', 'default']],
        )
        ->expectsTable(
            ['->requiredArgument', '->optionalArgument', '->defaultArgument'],
            [['Required', '', 'default']],
        );
});

it('can use array Argument', function () {
    artisan('test:argument:array item1 item2 item3')
        ->expectsOutput('item1, item2, item3');
});

it('can use optional array Argument', function () {
    artisan('test:argument:array:optional')
        ->expectsOutput('Empty: ');
});

it('can use default array Argument', function () {
    artisan('test:argument:array:default')
        ->expectsOutput('Item 1, Item 2');
});

it('can use boolean options', function () {
    artisan('test:options --option')
        ->expectsOutput('option: true, true');

    artisan('test:options')
        ->expectsOutput('option: false, false');
});

it('can use option with shortcut', function () {
    artisan('test:options -S')
        ->expectsOutput('optionShortcut: true, true');

    artisan('test:options --optionShortcut')
        ->expectsOutput('optionShortcut: true, true');

    artisan('test:options')
        ->expectsOutput('optionShortcut: false, false');
});

it('can use option with alternative name', function () {
    artisan('test:options --alternative')
        ->expectsOutput('optionAlternativeName: true, true');

    artisan('test:options')
        ->expectsOutput('optionAlternativeName: false, false');
});

it('can use negatable option', function () {
    artisan('test:options --optionNegatable')
        ->expectsOutput('optionNegatable: true, true');

    artisan('test:options --no-optionNegatable')
        ->expectsOutput('optionNegatable: false, false');

    artisan('test:options')
        ->expectsOutput('optionNegatable: false, false');
});

it('can use option with values', function () {
    artisan('test:options --optionWithValue=value')
        ->expectsOutput('optionWithValue: value, value');

    expect(fn () => artisan('test:options --optionWithValue'))
        ->toThrow('The "--optionWithValue" option requires a value.');
});

it('can use option with nullable value', function () {
    artisan('test:options --optionWithNullableValue=value')
        ->expectsOutput('optionWithNullableValue: value, value');

    artisan('test:options --optionWithNullableValue')
        ->expectsOutput('optionWithNullableValue: , ');
});

it('can use option with default value', function () {
    artisan('test:options --optionWithDefaultValue=value')
        ->expectsOutput('optionWithDefaultValue: value, value');

    artisan('test:options --optionWithNullableValue')
        ->expectsOutput('optionWithDefaultValue: default, default');
});

it('can use option with array value', function () {
    artisan('test:options --optionArray=Item1 --optionArray=Item2 --optionArray=Item3')
        ->expectsOutput('optionArray: Item1 Item2 Item3, Item1 Item2 Item3');

    artisan('test:options')
        ->expectsOutput('optionArray: , ');
});

it('can use option with array default value', function () {
    artisan('test:options --optionDefaultArray=Item1 --optionDefaultArray=Item2')
        ->expectsOutput('optionDefaultArray: Item1 Item2, Item1 Item2');

    artisan('test:options')
        ->expectsOutput('optionDefaultArray: default1 default2, default1 default2');
});

it('can cast enums', function () {
    artisan('test:enum B "String B" 2 --enum=B --stringEnum="String B" --intEnum=2');
});


