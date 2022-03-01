❌❌THIS REPO IS NO LONGER SUPPORTED PLEASE USE https://github.com/thettler/laravel-console-toolkit❌❌




# Laravel Command Attribute Syntax

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thettler/laravel-command-attribute-syntax.svg?style=flat-square)](https://packagist.org/packages/thettler/laravel-command-attribute-syntax)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/thettler/laravel-command-attribute-syntax/run-tests?label=tests)](https://github.com/thettler/laravel-command-attribute-syntax/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/thettler/laravel-command-attribute-syntax/Check%20&%20fix%20styling?label=code%20style)](https://github.com/thettler/laravel-command-attribute-syntax/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/thettler/laravel-command-attribute-syntax.svg?style=flat-square)](https://packagist.org/packages/thettler/laravel-command-attribute-syntax)

![Header Image](/.github/header_img.png)

This package lets you use PHP Attributes to define your Arguments and Options on Artisan
Commands. 
You do not need to touch the `handle()` method in existing commands in order to use this package. 
Supports all Laravel features and more:
- Negatable Options
- Required Value Options
- Enum Types
- Casting

## :purple_heart:  Support me

Visit my blog on [https://bitbench.dev](https://bitbench.dev) or follow me on Social Media 
[Twitter @bitbench](https://twitter.com/bitbench)
[Instagram @bitbench.dev](https://www.instagram.com/bitbench.dev/)

## :package:  Installation

You can install the package via composer:

```bash
composer require thettler/laravel-command-attribute-syntax
```

## :wrench:  Usage

> :right_anger_bubble:  Before you use this package you should already have an understanding of Artisan Commands. You can read about them [here](https://laravel.com/docs/8.x/artisan).

### A Basic Command

To use the attributes in your commands you first need to replace the default `\Illuminate\Console\Command` class
with `Thettler\LaravelCommandAttributeSyntax\Command`. 
Then add the `Thettler\LaravelCommandAttributeSyntax\Attributes\CommandAttribute` to the class.

The `CommandAttribute` requires the `name` parameter to be set. 
This will be the name of the Command which you can use to call it from the commandline.

```php
<?php
namespace App\Console\Commands;

use Thettler\LaravelCommandAttributeSyntax\Attributes\ArtisanCommand;
use Thettler\LaravelCommandAttributeSyntax\Command;

#[ArtisanCommand(
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

And call it like:

```bash
php artisan basic
```

<details><summary>Traditional Syntax</summary>
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
the `CommandAttribute` like this:

```php
#[CommandAttribute(
    name: 'basic',
    description: 'Some useful description.',
    help: 'Some helpful text.',
    hidden: true
)]
class BasicCommand extends Command
{
    ...
}
```

I like to use named arguments for a more readable look.

<details><summary>Traditional Syntax</summary>
<p>

```php
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

### Defining Input Expectations
The basic workflow to add an argument or option is always to add a property and decorate it with an Attribute.
`#[Option]` if you want an option and  `#[Argument]`if you want an argument.
The property will be hydrated with the value from the command line, so you can use it like any normal
property inside your `handle()` method. It's also possible to access the arguments and options via the
normal laravel methods `$this->argument('propertyName')` or `$this->option('propertyName')`.

More about that in the following sections. :arrow_down: 

> :exclamation: The property will only be hydrated inside of the `handle()` method. Keep that in mind.


### Arguments

To define Arguments you create a property and add the `Argument` attribute to it. 
The property will be hydrated with the value from the command line, so you can use it like any normal 
property inside your `handle()` method.

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
        $this->line($this->myArgument);
        $this->line($this->argument('myArgument'));
    }
}
```

call it like:

```bash
php artisan basic myValue
# Output:
# myValue
# myValue
```

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument}';
    
    public function handle() {
        $this->line($this->argument('myArgument'));
    }
}
```
</p>
</details>

#### Array Arguments

You can also use arrays in arguments, simply typehint the property as `array`.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected array $myArray;
    
    public function handle() {
        $this->line(implode(', ', $this->myArray));
    }
}
```

Call it like:

```bash
php artisan basic Item1 Item2 Item3 
# Output
# Item1, Item2, Item3 
```

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument*}';
    
    public function handle() {
        $this->line($this->argument('myArgument'));
    }
}
```

</p>
</details>

#### Optional Arguments

Of course, you can use optional arguments as well. To achieve this you simply make the property nullable.

> :information_source: This works with `array` as well but the property won't be null but an empty array
> instead

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected ?string $myArgument;
    
    ...
}
```

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument?}';
    
    ...
}
```

</p>
</details>

If your argument should have a default value, you can assign a value to the property which will be used 
as default value.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument]
    protected string $myArgument = 'default';
    
    ...
}
```

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument=default}';
    
    ...
}
```

</p>
</details>

#### Argument Description

You can set a description for arguments as parameter on the `Argument` Attribute.

```php
#[CommandAttribute(
    name: 'basic',
)]
class BasicCommand extends Command
{
    #[Argument(
        description: 'Argument Description'
    )]
    protected string $myArgument;
    
    ...
}
```

<details><summary>Traditional Syntax</summary>
<p>

```php
...
class BasicCommand extends Command
{
    protected $signature = 'basic {myArgument: Argument Description}';
    
    ...
}
```

</p>
</details>

> :exclamation: :exclamation: If you have more than one argument the order inside the class will also be the order on the commandline

### Options

To use options in your commands you use the `Options` Attribute. 
If you have set a typehint of `boolean` it will be false if the option was not set and true if it was set.

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
        dump($this->myOption);
        dump($this->option('myOption'));
    }
}
```

Call it like:

```bash
php artisan basic --myOption
# Output
# true
# true
```

```bash
php artisan basic
# Output
# false
# false
```

<details><summary>Traditional Syntax</summary>
<p>

```php
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

You can add a value to an option if you type hint the property with something different as `bool`. 
This will automatically make it to an option with a value.
If your typehint is not nullable the option will have a required value. 
This means the option can only be used with a value.

:x: Wont work `--myoption` :white_check_mark: works `--myoption=myvalue`

If you want to make the value optional simply make the type nullable or assign a value to the property

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

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    // requiredValue is not possible
    // defaultArray is not possible
    protected $signature = 'basic {--optionalValue=} {--defaultValue=default} {--array=*}';
   
   ...
}
```

</p>
</details>

#### Option Description

You can set a description for an option on the `Option` Attribute.

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

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {--option: Option Description}';
}
```

</p>
</details>

#### Option Shortcuts

You can set a shortcut for an option on the `Option` Attribute.  

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

<details><summary>Traditional Syntax</summary>
<p>

```php
class BasicCommand extends Command
{
    protected $signature = 'basic {--Q|option}';
}
```

</p>
</details>

#### Option alias

By default, the option name used on the commandline will be same as the property name. 
You can change this with the `name` parameter on the `Option` Attribute. 
This can be handy if you have conflicting property names or want a more expressive api for your commands.

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

#### Negatable Options
You can make option Negatable by adding the negatable parameter to the `Option` Attribute.
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

#### Enum Types
It is also possible to type `Arguments` or `Options` as Enum. The Package will automatically cast the input from the 
commandline to the typed Enum. If you use BackedEnums you use the value of the case and if you have a non backed Enum you use the name of the Case.

```php
enum Enum
{
    case A;
    case B;
    case C;
}

enum IntEnum: int
{
    case A = 1;
    case B = 2;
    case C = 3;
}

enum StringEnum: string
{
    case A = 'String A';
    case B = 'String B';
    case C = 'String C';
}
```

```php
    #[Argument]
    protected Enum $argEnum;

    #[Argument]
    protected StringEnum $argStringEnum;

    #[Argument]
    protected IntEnum $argIntEnum;

    #[Option]
    protected Enum $enum;

    #[Option]
    protected StringEnum $stringEnum;

    #[Option]
    protected IntEnum $intEnum;
```
```bash
php artisan enum B "String B" 2 --enum=B --stringEnum="String B" --intEnum=2
```

#### Casts
It's also possible to define your own casts. To do so you need to create a class that implements the `CastInterface`.
The `match()` method checks if a type can be cast by this cast-class and returns `true` if it is possible and false if not.

Let's have a look at small UserCast that allows to simply use the id of a user model on the command line and automatically fetch the
correct user from the database: 

```php
<?php

namespace Thettler\LaravelCommandAttributeSyntax\Casts;

use Thettler\LaravelCommandAttributeSyntax\Contracts\CastInterface;

class UserCast implements CastInterface
{
     public static function match(string $typeName, mixed $value): bool
    {
        return $typeName === User::class;
    }

    public function cast(mixed $value, string $typeName): User
    {
        return User::find($value);
    }
}
```

To register your cast you need to publish the config file first: 

```bash
php artisan vendor:publish --tag="command-attribute-syntax-config"
```
and add your cast to the cast array: 

```php
return [
    'casts' => [
            \Thettler\LaravelCommandAttributeSyntax\Casts\UserCast::class
    ]
];
```

The package goes top to bottom through the array and uses the first cast that returns `true` from the `match()` method.

Now finally typehint our Argument (or Option).
```php
#[CommandAttribute(name: 'userName')]
class UserNameCommand extends \Thettler\LaravelCommandAttributeSyntax\Command
{
    #[Argument]
    protected User $user;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line($this->user->name);
    }
}
```

```bash
php artisan userName 2
 // Some Name
```

## :robot:  Testing

```bash
composer test
```

## :open_book:  Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## :angel:  Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## :lock:  Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## :copyright:  Credits

- [Tobias Hettler](https://github.com/thettler)
- [All Contributors](../../contributors)

## :books:  License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
