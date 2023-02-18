<?php

declare(strict_types=1);

namespace Blue\Core\Application\Error;

use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Template;
use Blue\Core\View\ViewComponent;

/**
 * @property string $status
 * @property string $reason,
 * @property string|null $basePath
 */
class Error extends ViewComponent
{
    public function render(): array
    {
        return [
            Document::class => [
                'title' => 'Oops!',
                'body' => Template::include(__DIR__ . '/Error.phtml', [
                    'status' => $this->status,
                    'reason' => $this->reason,
                    'basePath' => $this->basePath ?? '/'
                ])
            ]
        ];
    }
}
