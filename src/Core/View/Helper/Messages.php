<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\Import;
use Blue\Core\View\ViewComponent;

/**
 * @property array $messages
 * @property array $validations
 */
#[Import(__DIR__ . '/Messages.ts')]
class Messages extends ViewComponent
{
    public static function include(array $messages, array $validations): static
    {
        $component = static::new();
        $component->messages = $messages;
        $component->validations = $validations;
        return $component;
    }

    public function render(): array
    {
        return [
            Template::include(
                __DIR__ . '/Messages.phtml',
                [
                    'messages' => $this->messages,
                    'validations' => $this->validations,
                ]
            )
        ];
    }
}
