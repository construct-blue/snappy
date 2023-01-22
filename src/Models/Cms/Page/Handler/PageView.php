<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Page\Handler;

use Blue\Core\View\PageWrapper;
use Blue\Core\View\ViewComponent;

/**
 * @property string $title
 * @property string $description
 * @property string $header
 * @property string $main
 * @property string $footer
 */
class PageView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => $this->title ?? '',
                'description' => $this->description ?? '',
                'body' => [
                    'header' => $this->header ?? '',
                    'main' => $this->main ?? '',
                    'footer' => $this->footer ?? '',
                ],
            ],
        ];
    }
}
