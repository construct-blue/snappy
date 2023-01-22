<?php

declare(strict_types=1);

namespace Blue\Models\User;

enum UserPermission
{
    case ACCOUNT;
    case CMS;
    case SETTINGS;
    case ANALYTICS;
}
