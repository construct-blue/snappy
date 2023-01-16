<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Blue\Core\View\Entrypoint;
use Blue\Core\View\PageViewComponent;
use Blue\Core\View\TemplateViewComponent;
use Blue\Core\View\ViewComponent;

/**
 * @property string $token
 * @property array $messages
 */
#[Entrypoint(__DIR__ . '/Login.ts')]
class Login extends ViewComponent
{
    public function render(): array
    {
        return [
            PageViewComponent::class => [
                'title' => 'Login',
                'body' => TemplateViewComponent::forTemplate(__DIR__ . '/Login.phtml')
            ]
        ];
    }
}
