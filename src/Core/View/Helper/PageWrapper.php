<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\Application\Session\Session;
use Blue\Core\View\ClientResources;
use Blue\Core\View\ViewComponent;

/**
 * @property string $lang
 * @property string $title
 * @property string $description
 * @property ClientResources $resources
 * @property array $body
 * @property array $content
 * @property array $after
 * @property null|array $head
 * @property null|Session $session
 */
class PageWrapper extends ViewComponent
{
    public static function for(string $title, string $description, array $body): static
    {
        $component = new static();
        $component->title = $title;
        $component->description = $description;
        $component->body = $body;
        return $component;
    }

    public function __prepare(string $id, array $params): static
    {
        $this->lang = $this->language ?? 'en';
        return parent::__prepare($id, $params);
    }

    public function render(): array
    {
        return [
            '<!DOCTYPE html>',
            'html lang="{lang}"' => [
                'head' => [
                    'title' => $this->title,
                    '<meta name="viewport" content="width=device-width, initial-scale=1" />',
                    '<meta charset="UTF-8">',
                    '<link rel="icon" href="/favicon.ico" sizes="any">',
                    '<link rel="apple-touch-icon" href="/apple-touch-icon.png">',
                    '<link rel="manifest" href="/manifest.webmanifest">',
                    $this->buildMetaDescriptionTag(),
                    $this->head ?? '',
                    Stylesheets::create(),
                ],
                'body' => RenderFirst::from([
                    Development::create(),
                    $this->body ?? [],
                    $this->content ?? [],
                    $this->after ?? [],
                    Scripts::create(),
                    Messages::create(),
                ])
            ]
        ];
    }

    private function buildMetaDescriptionTag(): string
    {
        if (isset($this->description)) {
            return "<meta name=\"description\" content=\"{$this->description}\"/>";
        }
        return '';
    }
}
