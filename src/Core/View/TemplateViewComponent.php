<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\MissingPropertyException;

use function ob_get_clean;
use function ob_start;

class TemplateViewComponent extends ViewComponent
{
    private string $template;

    public static function forTemplate(string $templateFile): static
    {
        $component = new static();
        $component->template = $templateFile;
        return $component;
    }

    /**
     * @return array
     * @throws MissingPropertyException
     */
    public function render(): array
    {
        if (!isset($this->template)) {
            throw MissingPropertyException::forComponent('Missing template', $this);
        }
        try {
            ob_start();
            include $this->template;
        } finally {
            $result = [ob_get_clean()];
        }
        return $result;
    }

    public function __debugInfo(): array
    {
        $data = parent::__debugInfo();
        if (isset($this->template)) {
            $data['name'] = $this->template;
        }
        return $data;
    }
}
