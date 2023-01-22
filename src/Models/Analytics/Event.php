<?php

declare(strict_types=1);

namespace Blue\Models\Analytics;

enum Event: string
{
    case PAGE_HIDDEN = 'page_hidden';
}
