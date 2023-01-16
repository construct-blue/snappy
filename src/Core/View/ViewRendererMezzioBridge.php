<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\View\Exception\ViewException;

class ViewRendererMezzioBridge implements TemplateRendererInterface
{
    private array $defaultParams = [];

    public function __construct(private readonly ViewRenderer $renderer)
    {
    }

    public function render(string $name, $params = []): string
    {
        $component = new $name();
        if (isset($params['action'])) {
            $this->renderer->action($component, $params['action'], $params);
        }
        $params = array_replace_recursive($this->getDefaultParams($name), $params);
        return $this->renderer->render($component, $params);
    }

    private function getDefaultParams(string $name): array
    {
        $componentParams = $this->defaultParams[$name] ?? [];
        $globalParams = $this->defaultParams[self::TEMPLATE_ALL] ?? [];
        return array_replace_recursive($globalParams, $componentParams);
    }

    public function addPath(string $path, ?string $namespace = null): void
    {
        $this->throwUnsupportedException();
    }

    public function getPaths(): array
    {
        return [];
    }

    private function throwUnsupportedException()
    {
        throw new ViewException('Paths currently not suppoerted for components.');
    }

    public function addDefaultParam(string $templateName, string $param, $value): void
    {
        $this->defaultParams[$templateName][$param] = $value;
    }
}
