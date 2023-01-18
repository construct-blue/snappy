<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error\NotFound;

use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

class NotFound extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Oops!',
                'body' => TemplateViewComponent::forTemplate(__DIR__ . '/NotFound.phtml')
            ],
        ];
    }
}
