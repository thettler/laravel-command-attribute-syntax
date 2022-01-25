<?php

it('can test', function () {
    \Pest\Laravel\artisan('test:basic')
        ->expectsOutput('Works!')
        ->expectsTable(
            ['Description', 'Help', 'Hidden'],
            [['Basic Command description!', 'Some Help.', false]],
        );
});

it('can use Arguments', function () {
    \Pest\Laravel\artisan('test:argument Test')
        ->expectsOutput('Works!')
        ->expectsOutput('Test');
});
