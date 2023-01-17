<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Core\View\Component\Details\Details;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Logic\Block\Block;
use Blue\Snapps\Cms\CmsFooter;
use Blue\Snapps\Cms\CmsNavigation;

/**
 * @property null|string $snapp
 * @property array $snapps
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
                        'h1' => [
                            'Blocks for ',
                            'select onchange="window.location = `{basePath}/blocks/${this.value}`"' => [
                                array_map(
                                    fn(string $code, string $name) => ($this->snapp ?? '') === $code ?
                                        ["option value=\"$code\" selected" => $name] :
                                        ["option value=\"$code\"" => $name],
                                    array_keys($this->snapps),
                                    array_values($this->snapps)
                                ),
                            ],
                        ],
                    ],
                    'main id="main"' => [
                        array_map(fn($message) => ['mark' => $message], $this->messages),
                        'form is="reactive-form" method="post" action="{basePath}/blocks/add/{snapp}"' => [
                            '<input type="text" name="code" placeholder="Code to add" required>',
                            'button type="submit"' => [
                                Icon::class => [
                                    'icon' => 'plus',
                                ],
                                'span' => 'Add block'
                            ],
                        ],
                        array_map(
                            fn(Block $block) => [
                                Form::class => [
                                    'id' => $block->getId(),
                                    'code' => $block->getCode(),
                                    'method' => 'post',
                                    'action' => '{basePath}/blocks/save/{snapp}',
                                    'content' => [
                                        '<input type="hidden" name="id" value="{id}"/>',
                                        Details::class => [
                                            'summary' => [
                                                Icon::class => [
                                                    'icon' => 'edit'
                                                ],
                                                ' ',
                                                'span' => '{code}',
                                            ],
                                            'content' => [
                                                'p' => [
                                                    '<input type="text" name="code" value="{code}" required>',
                                                    'button type="submit"' => [
                                                        Icon::class => [
                                                            'icon' => 'save',
                                                        ],
                                                        'span' => 'Save'
                                                    ],
                                                    'button is="confirm-button" '
                                                    . 'message="Sure?" type="submit" '
                                                    . ' formaction="{basePath}/blocks/delete/{snapp}"' => [
                                                        Icon::class => [
                                                            'icon' => 'trash-2'
                                                        ],
                                                        'span' => 'Delete',
                                                    ],
                                                ],
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
