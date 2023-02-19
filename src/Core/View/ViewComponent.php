<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\InvalidModelException;
use Blue\Core\View\Exception\UndefinedPropertyException;

/**
 * @template T of ViewModelInterface
 */
#[Import(__DIR__ . '/ViewComponent.ts')]
abstract class ViewComponent implements ViewComponentInterface
{
    private string $id;

    /**
     * @param ViewModelInterface&T $model
     */
    final private function __construct(private readonly ViewModelInterface $model)
    {
        $this->init();
    }

    /**
     * @param class-string<T> $class
     * @return void
     * @throws InvalidModelException
     */
    protected function assertModel(string $class): void
    {
        if (!$this->getModel() instanceof $class) {
            throw InvalidModelException::forComponent(
                sprintf(
                    'Component %s requires model instance of %s, %s given.',
                    static::class,
                    $class,
                    get_class($this->getModel())
                ),
                $this
            );
        }
    }

    protected function init()
    {
    }

    public static function new(ViewModelInterface|array $model = [], array $params = []): static
    {
        if (is_array($model)) {
            $model = new ViewModel($model);
        }
        $model->replaceValues($params);
        return new static($model);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return T
     */
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
