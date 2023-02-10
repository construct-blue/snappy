<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Core\View\Helper\PageWrapper;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Models\Analytics\Tracker\Client\Analytics;

/**
 * @property string $header
 * @property string $main
 * @property string $footer
 */
class Home extends ViewComponent
{
    public function render(): array
    {
        return [
            PageWrapper::class => [
                'title' => 'Robert Kleinschuster',
                'body' => [
                    Template::include(__DIR__ . '/Home.phtml'),
                    'main' => $this->main ?? '',
                    'footer' => $this->footer ?? '',
                ],
                'after' => Analytics::create()
            ],
        ];
    }
}
