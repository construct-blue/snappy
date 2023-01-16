<?php

declare(strict_types=1);

namespace Blue\SnApp\System\Analytics;

use Blue\SnApp\System\SystemFooter;
use Blue\SnApp\System\SystemNavigation;
use Blue\Core\Analytics\Day;
use Blue\Core\I18n\Language;
use Blue\Core\I18n\Region;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Icon\Icon;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property Day $summary
 * @property string[] $codes
 */
class AnalyticsView extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Analytics',
                'body' => [
                    'header' => [
                        SystemNavigation::class => [],
                        'h1' => 'Analytics',
                    ],
                    'main' => [
                        [
                            'section id="summary"' => [
                                Form::class => [
                                    'method' => 'post',
                                    'action' => '{basePath}/analytics/refresh',
                                    'id' => '',
                                    'content' => [
                                        'button type="submit"' => [
                                            Icon::class => [
                                                'icon' => 'refresh-cw'
                                            ],
                                            'span' => 'Refresh'
                                        ],
                                        'select onchange="window.location = `{basePath}/analytics/${this.value}`"' => [
                                            array_map(
                                                fn(string $code) => $this->summary->getCode() === $code ?
                                                    ['option selected' => $code] :
                                                    ['option' => $code],
                                                $this->codes
                                            ),
                                        ],
                                    ]
                                ],
                                'article' => [
                                    'h4' => "{$this->summary->getVisits()} total visits",
                                    "p" => [
                                        'cite' => <<<EOF
Desktop: {$this->summary->getDesktopVisits()}, Mobile: {$this->summary->getMobileVisits()}
EOF
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Visits by url',
                                            array_map(
                                                fn($key, $value) => ['p' => $key . ': ' . $value],
                                                array_keys($this->summary->getUrlVisits()),
                                                array_values($this->summary->getUrlVisits())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Visits by device',
                                            array_map(
                                                fn($key, $value) => ['p' => $key . ': ' . $value],
                                                array_keys($this->summary->getDeviceVisits()),
                                                array_values($this->summary->getDeviceVisits())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Duration by url',
                                            array_map(
                                                fn($key, $value) => ['p' => $key . ': ' . $value . ' s'],
                                                array_keys($this->summary->getUrlDuration()),
                                                array_values($this->summary->getUrlDuration())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Visits by language',
                                            array_map(
                                                fn($key, $value) => [
                                                    'p' => (Language::tryFrom($key)?->getName() ?? $key) . ': ' . $value
                                                ],
                                                array_keys($this->summary->getLanguageVisits()),
                                                array_values($this->summary->getLanguageVisits())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Visits by country',
                                            array_map(
                                                fn($key, $value) => [
                                                    'p' => (Region::tryFrom(
                                                        $key
                                                    )?->getFlag() ?? $key) . ' ' . Region::tryFrom(
                                                        $key
                                                    )?->getName() . ': ' . $value
                                                ],
                                                array_keys($this->summary->getRegionVisits()),
                                                array_values($this->summary->getRegionVisits())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Referrers',
                                            array_map(
                                                fn($key, $value) => ['p' => $key . ': ' . $value],
                                                array_keys($this->summary->getReferrers()),
                                                array_values($this->summary->getReferrers())
                                            )
                                        ]
                                    ],
                                    [
                                        'section' => [
                                            'h5' => 'Actions',
                                            array_map(
                                                fn($key, $value) => ['p' => $key . ': ' . $value],
                                                array_keys($this->summary->getActions()),
                                                array_values($this->summary->getActions())
                                            )
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                    SystemFooter::class => [],
                ],
            ],
        ];
    }
}
