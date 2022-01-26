# Laravel Command Attribute Syntax

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thettler/laravel-command-attribute-syntax.svg?style=flat-square)](https://packagist.org/packages/thettler/laravel-command-attribute-syntax)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/thettler/laravel-command-attribute-syntax/run-tests?label=tests)](https://github.com/thettler/laravel-command-attribute-syntax/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/thettler/laravel-command-attribute-syntax/Check%20&%20fix%20styling?label=code%20style)](https://github.com/thettler/laravel-command-attribute-syntax/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/thettler/laravel-command-attribute-syntax.svg?style=flat-square)](https://packagist.org/packages/thettler/laravel-command-attribute-syntax)

![Header Image](/.github/header_img.png)

Use PHP Attributes instead of the Laravel `$signiture` property to define your Arguments and Options on Artisan
Commands. Can be used with existing commands without the need to touch the `handle()` method. 
Supports all Laravel features plus:
- Negatable Options
- Required Value Options

## Support me

Visit my blog on [bitbench.dev](bitbench.dev) or follow me on Social
Media [Twitter @bitbench](https://twitter.com/bitbench)
, [Instagram @bitbench.dev](https://www.instagram.com/bitbench.dev/)

## Installation

You can install the package via composer:

```bash
composer require thettler/laravel-command-attribute-syntax
```

## Usage

> Before you use this package you should already have an understanting of Artisan Commands. You can read about them [here](https://laravel.com/docs/8.x/artisan).

### A Basic Command

To use the attributes in your commands you first need to extend your command
with `Thettler\LaravelCommandAttributeSyntax\Command`. Then add
the `Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute` to the class:
The `CommandAttribute` requires the `name`paramenter to be set. This will be the name of the Command which you can use
to call it from the commandline.

```php
<?php
namespace App\Console\Commands;

use Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    public function handle()
    {
        return 1;
    }
}
```

And call it with.

```bash
php artisan basic
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class BasicCommand extends Command
{
    protected $signature = 'basic';


    public function handle()
    {
        return 1;
    }
}
```

</p>
</details>

### Descriptions, Help and Hidden Commands

If you want to add a description, a help comment or mark the command as hidden, you can specify this on
the `CommandAttribute`

```php
...
#[CommandAttribute(
    name: 'basic',
    description: 'Some usefull description.',
    help: 'Some helpfull text.',
    hidden: true
)]
class BasicCommand extends Command
{
    ...
}
```

And call it like.

```bash
php artisan basic
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic';

    protected $description = 'Some usefull description.';

    protected $help = 'Some helpfull text.';
    
    protected $hidden = true;
    ...
}
```

</p>
</details>

## Defining Input Expectations

### Arguments

To define Arguments you create a property and add the `Argument` attribute to it. The value will be passed through to
the property and can normally be used in the `handle()`.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected string $myArgument;
    
    public function handle() {
        dump($this->myArgument); //the input from the commandline
        dump($this->argument('myArgument')); // does also still work
    }
}
```

Call it like:

```bash
php artisan basic myValue
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument}';
    
    public function handle() {
        dump($this->argument('myArgument'));
    }
}
```

</p>
</details>

#### Array Arguments

You can also use arrays in arguments, simply typehint the property as Array or as nullable Array if you want your
Argument to be optional.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected array $myArray;
    
    public function handle() {
        dump($this->myArray); //the input from the commandline
        dump($this->argument('myArray')); // does also still work
    }
}
```

Call it like:

```bash
php artisan basic Item1 Item2 Item3 
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument*}';
    
    public function handle() {
        dump($this->argument('myArgument'));
    }
}
```

</p>
</details>

#### Optional Arguments

Of course, you can use optional arguments as well. To achieve this you simply make the property nullable.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected ?string $myArgument;
    
    public function handle() {
        dump($this->myArgument); //null
        dump($this->argument('myArgument')); // null
    }
}
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument?}';
    
    public function handle() {
        dump($this->argument('myArgument')); // null
    }
}
```

</p>
</details>

If you want your argument to have a default value you can simpy add it to the property.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected string $myArgument = 'default';
    
    public function handle() {
        dump($this->myArgument); // default
        dump($this->argument('myArgument')); // default
    }
}
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument=default}';
    
    public function handle() {
        dump($this->argument('myArgument')); // default
    }
}
```

</p>
</details>

Call it like:

```bash
php artisan basic
```

#### Argument Description

You can set a description for arguments on the `Argument` Attribute.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Argument;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument(
        description: 'Argument Description'
    )]
    protected string $myArgument;
    
    public function handle() {
        dump($this->myArgument); // default
        dump($this->argument('myArgument')); // default
    }
}
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument: Argument Description}';
    
    public function handle() {
        dump($this->argument('myArgument')); // default
    }
}
```

</p>
</details>

> :exclamation: If you have more than one argument the order inside the class will also be the order in the commandline

### Options

To use options in your commands you use the `Options` Attribute. If you have set a typehint of `boolean` it will be
false if the option was not set and true if it was set.

```php
use \Thettler\LaravelCommandAttributeSyntax\Attributes\Option;

#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option]
    protected bool $myOption;
    
    public function handle() {
        dump($this->myOption); // the input from the commandline
        dump($this->option('myOption')); // does also still work
    }
}
```

Call it like:

```bash
php artisan basic --myOption
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {--myOption}';
    
    public function handle() {
        dump($this->option('myOption'));
    }
}
```

</p>
</details>

#### Value Options

You can add a value to an option if you type hint the property not as bool. This will automatically make it an option
with a value.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option]
    protected string $requiredValue; // if the option is used the User must specify a value  
    
    #[Option]
    protected ?string $optionalValue; // The value is optional

    #[Option]
    protected string $defaultValue = 'default'; // The option has a default value

    #[Option]
    protected array $array; // an Array Option 

    #[Option]
    protected array $defaultArray = ['default1', 'default2']; // an Array Option with default
    ...
}
```

Call it like:

```bash
php artisan basic --requiredValue=someValue --optionalValue --array=Item1 --array=Item2
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
    
<?php
...
class BasicCommand extends Command
{
    // requiredValue is not possible
    // defaultArray is not possible
    protected $signature = 'basic {--optionalValue=} {--defaultValue=default} {--array=*}';
    
    public function handle() {
        dump($this->option('myOption'));
    }
}
```

</p>
</details>

#### Option Description

You can set a description for options on the `Option` Attribute.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option(
        description: 'Option Description'
    )]
    protected bool $option;
    ...
}
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {--option: Option Description}';
}
```

</p>
</details>

#### Option Shortcuts

You can set a shortcut for options on the `Option` Attribute.
> :warning: Be aware that a shortcut can only be one char long

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option(
        shortcut: 'Q'
    )]
    protected bool $option;
    ...
}
```

Call it like:

```bash
php artisan basic -Q
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {--Q|option}';
}
```

</p>
</details>

#### Option alias

By default the option named used on the commandline will be same as the property name. You can change this with
the `name` parameter on the `Option` Attribute.

> :warning: If you use the `->option()` syntax you need to specify the alias name to get the option.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option(
        name: 'alternativeName'
    )]
    protected bool $myOption;
    
    public function handle(){
       dump($this->myOption);
       dump($this->option('alternativeName'))
    }
}
```

Call it like:

```bash
php artisan basic --alternativeName
```

<details><summary>Tratitional Syntax</summary>
<p>

```php
<?php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {--Q|option}';
}
```

</p>
</details>

#### Negatable Options
You can make option Negatable by adding the negatable parameter to the `Option` Attribute
Now the option accepts either the flag (e.g. --yell) or its negation (e.g. --no-yell).

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Option(
        negatable: true
    )]
    protected bool $yell;
    
    public function handle(){
       dump($this->yell); // true if called with --yell
       dump($this->yell); // false if called with --no-yell
    }
}
```

Call it like:

```bash
php artisan basic --yell
php artisan basic --no-yell
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Tobias Hettler](https://github.com/thettler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
