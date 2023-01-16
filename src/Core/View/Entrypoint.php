<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Entrypoint
{
    public function __construct(public string $file)
    {
    }
}
