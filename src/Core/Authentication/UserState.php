<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\Util\BackedEnumTrait;

enum UserState: string
{
    use BackedEnumTrait;

    case ACTIVE = 'active';
    case LOCKED = 'locked';
}
