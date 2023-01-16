<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\Util\BackedEnumTrait;

enum UserOption: string
{
    use BackedEnumTrait;

    case ALLOW_TRACKING = 'allow_tracking';
}
