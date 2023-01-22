<?php

declare(strict_types=1);

namespace Blue\Models\User;

enum UserPermission
{
    case ACCOUNT;
    case CMS;
    case ALL_SNAPPS;
    case SETTINGS;
    case ANALYTICS;
}
