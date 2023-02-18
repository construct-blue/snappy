<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block\View;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\Component\Link;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\ViewComponent;
use Blue\Models\Cms\Block\Block;
use Blue\Snapps\System\Cms\CmsHeader;
use Blue\Snapps\System\SystemFooter;

/**
 * @property SnappRoute $snapp
 * @property string $pagesPath
 * @property string $cmsBasePath
 * @property Block[] $blocks
 */
class BlockView extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'CMS',
                'messages' => $this->messages,
                'validations' => $this->validations,
                'body' => [
                    CmsHeader::new($this->getModel()),
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
                        BlockAddView::new($this->getModel()),
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
                                        'cmsBasePath' => $this->cmsBasePath,
                                        'snapp' => $this->snapp,
                                        'id' => $block->getId(),
                                        'code' => $block->getCode(),
                                        'content' => $block->getContent()
                                    ]
                                ]
                            ],
                        ], $this->blocks),
                    ],
                    SystemFooter::new($this->getModel())
                ]
            ],
        ];
    }
}
