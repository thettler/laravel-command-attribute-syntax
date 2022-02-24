<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Orchestra\Testbench\TestCase as Orchestra;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Thettler\LaravelCommandAttributeSyntax\LaravelCommandAttributeSyntaxServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelCommandAttributeSyntaxServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }

    protected function callCommand(\Illuminate\Console\Command $command, array $input = []): Command
    {
        $command = clone $command;

        $application = app();
        $command->setLaravel($application);

        $input = new ArrayInput($input);
        $output = new NullOutput();

        $command->run($input, $output);

        return $command;
    }

    /**
     * Get a database connection instance.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }
}
