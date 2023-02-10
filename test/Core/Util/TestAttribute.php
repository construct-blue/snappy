<?php
declare(strict_types=1);

namespace BlueTest\Core\Util;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class TestAttribute
{
    public function __construct(public string $value)
    {
    }
}