<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Cms\Page\Page;
use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\View\Component\Button\LinkButton;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Link;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsFooter;
use Blue\Snapps\Cms\CmsHeader;

/**
 * @property IngressRoute $snapp
 * @property IngressRoute $activeSnapp
 * @property Page[] $pages
 */
class PageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->snapp->getName() . ' - ' . $this->activeSnapp->getName(),
                'body' => [
                    CmsHeader::class => [
                        'basePath' => '{basePath}/pages'
                    ],
                    'main id="main"' => [
                        Link::class => [
                            'href' => '{basePath}/blocks/' . $this->snapp->getCode(),
                            'text' => [
                                Icon::class => [
                                    'icon' => 'file-text'
                                ],
                                ' Configure reusable blocks',
                            ]
                        ],
                        PageAddView::class => [],
                        array_map(fn(Page $page) => [
                            Details::class => [
                                'id' => $page->getId(),
                                'summary' => [
                                    'span' => $page->getCode(),
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
