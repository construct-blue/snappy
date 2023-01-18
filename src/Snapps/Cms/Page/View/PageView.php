<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Cms\Page\Page;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Select\LinkSelect;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsFooter;
use Blue\Snapps\Cms\CmsNavigation;

/**
 * @property string $snapp
 * @property array $snapps
 * @property Page[] $pages
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
                        'h1' => [
                            'Pages for ',
                            LinkSelect::class => [
                                'selected' => $this->snapp ?? '',
                                'options' => $this->snapps,
                                'hrefCallback' => fn($value) => "{basePath}/pages/$value",
                            ],
                        ],
                    ],
                    'main id="main"' => [
                        array_map(fn($message) => ['mark' => $message], $this->messages),
                        new PageAddView(),
                        array_map(fn(Page $page) => [
                            Details::class => [
                                'id' => $page->getId(),
                                'summary' => $page->getCode() ?? '',
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
