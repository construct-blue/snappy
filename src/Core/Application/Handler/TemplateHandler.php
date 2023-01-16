<?php

declare(strict_types=1);

namespace Blue\Core\Application\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler implements RequestHandlerInterface
{
    public function __construct(private TemplateRendererInterface $renderer)
    {
    }

    public function getRenderer(): TemplateRendererInterface
    {
        return $this->renderer;
    }

    public function render(string $name, $params = []): string
    {
        return $this->getRenderer()->render($name, $params);
    }

    public function assign(string $name, $value)
    {
        $this->getRenderer()->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, $name, $value);
    }
}
