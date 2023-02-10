<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block\View;

use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Link;
use Blue\Core\View\Helper\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Models\Cms\Block\Block;
use Blue\Snapps\System\Cms\CmsHeader;
use Blue\Snapps\System\SystemFooter;

/**
 * @property string $pagesPath
 * @property Block[] $blocks
 */
class BlockView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'CMS',
                'body' => [
                    CmsHeader::class => [],
                    'main id="main"' => [
                        Link::class => [
                            'href' => $this->pagesPath,
                            'text' => [
                                Icon::class => [
                                    'icon' => 'layout'
                                ],
                                ' Manage pages',
                            ]
                        ],
                        new BlockAddView(),
                        array_map(fn(Block $block) => [
                            Details::class => [
                                'id' => $block->getId(),
                                'summary' => [
                                    BlockSummaryView::class => [
                                        'code' => $block->getCode()
                                    ],
                                ],
                                'content' => [
                                    BlockEditView::class => [
                                        'id' => $block->getId(),
                                        'code' => $block->getCode(),
                                        'content' => $block->getContent()
                                    ]
                                ]
                            ],
                        ], $this->blocks),
                    ],
                    new SystemFooter()
                ]
            ],
        ];
    }
}
