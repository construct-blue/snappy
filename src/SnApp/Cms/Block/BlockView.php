<?php

declare(strict_types=1);

namespace Blue\SnApp\Cms\Block;

use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Logic\Block\Block;
use Blue\SnApp\Cms\CmsFooter;
use Blue\SnApp\Cms\CmsNavigation;

/**
 * @property array $messages
 * @property array $blocks
 */
class BlockView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Blocks - Pars CMS',
                'body' => [
                    'header' => [
                        CmsNavigation::class => [],
                        'h1' => 'Blocks',
                    ],
                    'main id="main"' => [
                        array_map(fn($message) => ['mark' => $message], $this->messages),
                        'form is="reactive-form" method="post" action="blocks/add"' => [
                            '<input type="text" name="code" placeholder="code">',
                            'button type="submit"' => [
                                Icon::class => [
                                    'icon' => 'plus',
                                ],
                                'span' => 'Add block'
                            ]
                        ],
                        array_map(
                            fn(Block $block) => [
                                Form::class => [
                                    'id' => $block->getId(),
                                    'code' => $block->getCode(),
                                    'method' => 'post',
                                    'action' => 'blocks/save',
                                    'content' => [
                                        '<input type="hidden" name="id" value="{id}"/>',
                                        Details::class => [
                                            'summary' => [
                                                Icon::class => [
                                                    'icon' => 'edit'
                                                ],
                                                '<input type="text" name="code" value="{code}">',
                                                'button type="submit"' => [
                                                    Icon::class => [
                                                        'icon' => 'save',
                                                    ],
                                                    'span' => 'Save'
                                                ],
                                                'button is="confirm-button" '
                                                . 'message="Sure?" type="submit" formaction="blocks/delete"' => [
                                                    Icon::class => [
                                                        'icon' => 'trash-2'
                                                    ],
                                                    'span' => 'Delete',
                                                ],
                                            ],
                                            'content' => [
                                                'textarea name="content"' => $block->getContent(),
                                            ]
                                        ],
                                    ]
                                ],
                            ],
                            $this->blocks
                        )
                    ],
                    CmsFooter::class => []
                ]
            ],
        ];
    }
}
