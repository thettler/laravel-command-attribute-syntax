<?php

namespace Thettler\LaravelCommandAttributeSyntax\Tests\Fixtures\Enums;

enum StringEnum: string
{
    case A = 'String A';
    case B = 'String B';
    case C = 'String C';
}
