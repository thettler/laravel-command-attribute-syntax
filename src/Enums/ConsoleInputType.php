<?php

namespace Thettler\LaravelCommandAttributeSyntax\Enums;

enum ConsoleInputType: string
{
    case Argument = 'argument';
    case Option = 'option';
}
