<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsNavigation;

/**
 * @property string $snapp
 * @property array $snapps
 * @property array $pages
 * @property array $messages
 */
class PageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Pages - CMS',
                'body' => [
                    'header' => [
                        CmsNavigation::fromParams([]),
                        'h1' => 'Pages'
                    ],
                    'main' => [

                    ],
                ],
            ]
        ];
    }
}
