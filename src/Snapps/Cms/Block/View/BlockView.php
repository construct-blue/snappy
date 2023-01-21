<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block\View;

use Blue\Cms\Block\Block;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;
use Blue\Snapps\Cms\CmsFooter;
use Blue\Snapps\Cms\CmsHeader;
use Blue\Snapps\Cms\SnappNavigation;

/**
 * @property null|string $snapp
 * @property array $snapps
 * @property array $blocks
 */
class BlockView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Blocks - CMS',
                'body' => [
                    CmsHeader::class => [
                        'basePath' => '{basePath}/blocks'
                    ],
                    'main id="main"' => [
                        'a href="{basePath}/pages/{snapp}"' => [
                            Icon::class => [
                                'icon' => 'layout'
                            ],
                            ' Manage pages',
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
                    CmsFooter::class => []
                ]
            ],
        ];
    }
}
