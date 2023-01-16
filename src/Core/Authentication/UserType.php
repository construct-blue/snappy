<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\Util\BackedEnumTrait;

enum UserType: string
{
    use BackedEnumTrait;

    case DEFAULT = 'default';
    case HIDDEN = 'hidden';
}
