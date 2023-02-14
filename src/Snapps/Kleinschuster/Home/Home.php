<?php

namespace Blue\Snapps\Kleinschuster\Home;

use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;
use Blue\Models\Analytics\Tracker\Client\Analytics;

/**
 * @property string $title
 * @property string $description
 * @property string $header
 * @property string $main
 * @property string $footer
 */
class Home extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => $this->title ?? 'robs.social',
                'description' => $this->description ?? 'Links zu meinen Social Media-Profilen.',
                'body' => [
                    Template::include(__DIR__ . '/Home.phtml'),
                    'main' => $this->main ?? '',
                    'footer' => $this->footer ?? '',
                ],
                'after' => Analytics::new()
            ],
        ];
    }
}
