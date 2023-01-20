<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Cms\Page\Page;
use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageWrapper;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property string $header
 * @property string $main
 * @property string $footer
 */
class Home extends ViewComponent
{
    public function render(): array
    {
        $parsedown = new \ParsedownExtra();
        return [
            PageWrapper::class => [
                'title' => 'Robert Kleinschuster',
                'body' => [
                    TemplateViewComponent::forTemplate(__DIR__ . '/Home.phtml'),
                    'main' => $this->main ?? '',
                    'footer' => $this->footer ?? '',
                ]
            ],
        ];
    }
}
