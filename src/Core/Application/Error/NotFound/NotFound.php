<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error\NotFound;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property array $header
 * @property array $footer
 * @property string $basePath
 */
class NotFound extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Oops!',
                'body' => [
                    'header' => [
                        $this->header ?? ['h1' => 'Oops!'],
                    ],
                    TemplateViewComponent::forTemplate(__DIR__ . '/NotFound.phtml'),
                    'footer' => [
                        'p' => [
                            'a href="#" onclick="window.location = document.referrer"' => [
                                Icon::for('arrow-left'),
                                'span' => 'Back'
                            ],
                            ' ',
                            'a href="{basePath}/"' => 'Home',
                        ],
                        $this->footer ?? [],
                    ]
                ]
            ],
        ];
    }
}
