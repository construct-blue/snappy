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

    /**
     * @param class-string<ViewComponentInterface> $name
     * @param array|object $params
     * @return string
     * @throws Exception\InvalidComponentClassException
     * @throws Exception\InvalidComponentContentException
     * @throws Exception\InvalidComponentParameterException
     * @throws ViewException
     */
    public function render(string $name, $params = []): string
    {
        $component = ViewRenderer::instantiateComponent($name, $params);
        return $this->renderer->render($component, $this->getDefaultParams($name));
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
