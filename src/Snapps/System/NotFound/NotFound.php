<?php

declare(strict_types=1);

namespace Blue\Snapps\System\NotFound;

use Blue\Core\View\Component\SnappyLogo;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\System\SystemFooter;
use Blue\Snapps\System\SystemNavigation;

class NotFound extends ViewComponent
{
    public function render(): array
    {
        return [
            \Blue\Core\Application\Error\NotFound\NotFound::class => [
                'basePath' => $this->basePath ?? '/',
                'header' => [
                    SystemNavigation::new($this->getModel()),
                ],
                'footer' => [
                    SystemFooter::new($this->getModel())
                ]
            ]
        ];
    }
}
