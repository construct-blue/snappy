<?php

declare(strict_types=1);

namespace Blue\Models\Analytics;

use Blue\Core\Util\BackedEnumTrait;

enum Event: string
{
    use BackedEnumTrait;

    case PAGE_SHOW = 'page_show';
    case PAGE_HIDE = 'page_hide';
}
