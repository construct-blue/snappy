<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error\NotFound;

use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property array $header
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
                        'h1' => 'Oops!',
                        $this->header ?? [],
                    ],
                    TemplateViewComponent::forTemplate(__DIR__ . '/NotFound.phtml'),
                    'footer' => [
                        'a href="{backPath}"' => [
                            Icon::class => [
                                'icon' => 'arrow-left'
                            ],
                            'span' => 'Back'
                        ],
                        ' ',
                        'a href="{basePath}/"' => 'Home'
                    ]
                ]
            ],
        ];
    }
}
