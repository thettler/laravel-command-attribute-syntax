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
        ->expectsOutput('Item 1, Item 2');
});
