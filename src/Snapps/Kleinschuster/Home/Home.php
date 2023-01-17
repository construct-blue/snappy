<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;
use Blue\Logic\Block\Block;

/**
 * @property array $blocks
 */
#[Entrypoint(__DIR__ . '/Home.ts')]
class Home extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Robert Kleinschuster',
                'body' => [
                    TemplateViewComponent::forTemplate(__DIR__ . '/Home.phtml'),
                    array_map(fn(Block $block) => $block->getContent(), $this->blocks)
                ]
            ],
        ];
    }
}
