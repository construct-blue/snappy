<?php

declare(strict_types=1);

namespace Blue\Core\View\Component\Toast;

enum ToastType: string
{
    case ERROR = 'error';
    case INFO = 'info';
    case SUCCESS = 'success';
}
