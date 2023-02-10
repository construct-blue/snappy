<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\Application\Session\Session;
use Blue\Core\View\ViewComponent;

/**
 * @property Session $session
 */
class Messages extends ViewComponent
{
    public function render(): array
    {
        if (isset($this->session)) {
            return [
                Template::include(
                    __DIR__ . '/Messages.phtml',
                    [
                        'messages' => $this->session->popMessages(),
                        'validations' => $this->session->popValidations(),
                    ]
                )
            ];
        }
        return [];
    }
}