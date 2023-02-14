<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Page\View;

use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Link;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;
use Blue\Models\Cms\Page\Page;
use Blue\Snapps\System\Cms\CmsHeader;
use Blue\Snapps\System\SystemFooter;

/**
 * @property string $blocksPath
 * @property Page[] $pages
 */
class PageView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'CMS',
                'body' => [
                    CmsHeader::class => [],
                    'main id="main"' => [
                        Link::class => [
                            'href' => $this->blocksPath,
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
                    SystemFooter::new()
                ],
            ],
        ];
    }
}
