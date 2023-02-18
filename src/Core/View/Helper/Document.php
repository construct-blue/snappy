<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\Application\Session\Session;
use Blue\Core\View\ClientResources;
use Blue\Core\View\Import;
use Blue\Core\View\ViewComponent;

/**
 * @property string $lang
 * @property string $title
 * @property string $description
 * @property array $body
 * @property array $content
 * @property array $after
 * @property null|array $head
 * @property null|Session $session
 */
#[Import(__DIR__ . '/Document.ts')]
class Document extends ViewComponent
{
    private ClientResources $resources;

    public static function for(string $title, string $description, array $body): static
    {
        $component = static::new();
        $component->title = $title;
        $component->description = $description;
        $component->body = $body;
        return $component;
    }

    public function setResources(ClientResources $resources): Document
    {
        $this->resources = $resources;
        return $this;
    }

    public function prepare(string $id, array $params): void
    {
        parent::prepare($id, $params);
        $this->lang = $this->language ?? 'en';
    }


    public function render(): array
    {
        return [
            '<!DOCTYPE html>',
            'html lang="' . $this->lang . '"' => [
                'head' => [
                    Template::include(__DIR__ . '/DocumentHead.phtml', [
                        'title' => $this->title,
                        'description' => $this->description ?? null,
                    ]),
                    $this->head ?? '',
                    // closures to defer execution after body is rendered
                    fn() => Stylesheets::include($this->resources->getCSSFiles()),
                    fn() => Scripts::include($this->resources->getJSFiles()),
                ],
                'body' => Body::include([
                    Info::new(),
                    $this->body ?? [],
                    $this->content ?? [],
                    $this->after ?? [],
                    Messages::include($this->messages ?? [], $this->validations ?? [])
                ])
            ]
        ];
    }
}
