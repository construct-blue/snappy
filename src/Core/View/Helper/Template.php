<?php

declare(strict_types=1);

namespace Blue\Core\View\Helper;

use Blue\Core\View\Exception\MissingPropertyException;
use Blue\Core\View\ViewComponent;

use Blue\Core\View\ViewModelInterface;
use function ob_get_clean;
use function ob_start;

class Template extends ViewComponent
{
    private string $template;
    private array $templateVars = [];

    public static function include(
        string                   $templateFile,
        array                    $templateVars = [],
        ViewModelInterface|array $model = []
    ): static
    {
        $component = static::new($model);
        $component->templateVars = $templateVars;
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
        extract($this->templateVars);
        $model = $this->getModel();
        try {
            ob_start();
            require $this->template;
        } finally {
            // always close output buffer
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
