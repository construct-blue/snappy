<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Cms\Page\Page;
use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property Page $page
 */
#[Entrypoint(__DIR__ . '/Home.ts')]
class Home extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Robert Kleinschuster',
                'body' => [
                    TemplateViewComponent::forTemplate(__DIR__ . '/Home.phtml'),
                    fn() => isset($this->page) ? [
                        'main' => $this->page->getMain()
                    ] : []
                ]
            ],
        ];
    }
}
