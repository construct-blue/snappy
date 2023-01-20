<?php

declare(strict_types=1);

namespace Blue\Cms\Page\Handler;

use Blue\Cms\Page\Page;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;

/**
 * @property Page $page
 */
class PageView extends ViewComponent
{
    public function render(): array
    {
        $parsedown = new \ParsedownExtra();

        return [
            PageWrapper::class => [
                'title' => $this->page->getTitle(),
                'description' => $this->page->getDescription(),
                'body' => [
                    'header' => $parsedown->text($this->page->getHeader()),
                    'main' => $parsedown->text($this->page->getMain()),
                    'footer' => $parsedown->text($this->page->getFooter()),
                ],
            ],
        ];
    }
}
