<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

enum MessageType: string
{
    case ERROR = 'error';
    case INFO = 'info';
    case SUCCESS = 'success';
}
