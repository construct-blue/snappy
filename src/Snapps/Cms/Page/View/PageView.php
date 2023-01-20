<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Cms\Page\Page;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Select\LinkSelect;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsFooter;
use Blue\Snapps\Cms\CmsNavigation;
use Blue\Snapps\Cms\SnappNavigation;

/**
 * @property string $snapp
 * @property array $snapps
 * @property Page[] $pages
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
                        CmsNavigation::class => [

                        ],
                        'h1' => [
                            'Content',
                        ],
                        SnappNavigation::class => [
                            'basePath' => '{basePath}/pages'
                        ],
                    ],
                    'main id="main"' => [
                        'a href="{basePath}/blocks/{snapp}"' => [
                            Icon::class => [
                                'icon' => 'file-text'
                            ],
                            ' Configure reusable blocks',
                        ],
                        PageAddView::class => [],
                        array_map(fn(Page $page) => [
                            Details::class => [
                                'id' => $page->getId(),
                                'summary' => [
                                    Icon::class => [
                                        'icon' => 'layout'
                                    ],
                                    ' ',
                                    $page->getCode() ?? ''
                                ],
                                'content' => [
                                    PageEditView::class => [
                                        'id' => $page->getId(),
                                        'code' => $page->getCode() ?? '',
                                        'title' => $page->getTitle(),
                                        'description' => $page->getDescription(),
                                        'header' => $page->getHeader(),
                                        'main' => $page->getMain(),
                                        'footer' => $page->getFooter(),
                                    ],
                                ],
                            ],
                        ], $this->pages),
                    ],
                    new CmsFooter()
                ],
            ],
        ];
    }
}
