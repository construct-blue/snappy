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
        return [
            PageWrapper::class => [
                'title' => $this->page->getTitle(),
                'description' => $this->page->getDescription(),
                'body' => [
                    'header' => $this->page->getHeader(),
                    'main' => $this->page->getMain(),
                    'footer' => $this->page->getFooter(),
                ],
            ],
        ];
    }
}
