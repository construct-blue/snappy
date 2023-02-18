<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\UndefinedPropertyException;

#[Import(__DIR__ . '/ViewComponent.ts')]
abstract class ViewComponent implements ViewComponentInterface
{
    private string $id;

    final private function __construct(private readonly ViewModelInterface $model)
    {
        $this->init();
    }

    protected function init()
    {
    }

    public static function new(ViewModelInterface|array $model = []): static
    {
        if (is_array($model)) {
            $model = new ViewModel($model);
        }
        return new static($model);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getModel(): ViewModelInterface
    {
        return $this->model;
    }

    public function prepare(string $id, array $params): void
    {
        $this->id = $id;
        $this->getModel()->replaceValues($params);
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws UndefinedPropertyException
     */
    public function __get(string $name): mixed
    {
        $result = $this->getModel()->get($name);
        if (null === $result) {
            throw UndefinedPropertyException::forComponent("Access to undefined property '$name'", $this);
        }
        return $result;
    }

    public function __isset(string $name): bool
    {
        return null !== $this->getModel()->get($name);
    }

    public function __set(string $name, $value): void
    {
        $this->getModel()->set($name, $value);
    }

    public function __debugInfo(): array
    {
        return [
            'name' => static::class,
            'model' => $this->model,
        ];
    }
}
