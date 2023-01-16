<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error\NotFound;

use Blue\Core\View\PageViewComponent;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

class NotFound extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Oops!',
                'body' => TemplateViewComponent::forTemplate(__DIR__ . '/NotFound.phtml')
            ],
        ];
    }
}
