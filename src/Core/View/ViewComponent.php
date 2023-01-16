<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\UndefinedMethodException;
use Blue\Core\View\Exception\UndefinedPropertyException;
use Blue\Core\View\Exception\ViewException;

use function array_replace_recursive;
use function is_callable;

#[Entrypoint(__DIR__ . '/ViewComponent.ts')]
abstract class ViewComponent implements ViewComponentInterface
{
    private string $id;
    private array $data = [];

    private ViewAction $action;

    private ?ViewComponentInterface $parent = null;

    final public function __construct()
    {
        $this->action = new ViewAction('');
        $this->init();
    }

    /**
     * @param class-string<ViewComponentInterface> $className
     * @return ViewComponentInterface
     * @throws InvalidComponentClassException
     */
    public static function fromClassName(string $className): ViewComponentInterface
    {
        if (!in_array(ViewComponentInterface::class, class_implements($className, false))) {
            throw new InvalidComponentClassException(
                "Component class $className must implement " . ViewComponentInterface::class
            );
        }
        return new $className();
    }

    public static function fromParams(array $params): static
    {
        $component = new static();
        $component->data = array_replace_recursive($component->data, $params);
        return $component;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function getAction(): ViewAction
    {
        return $this->action;
    }

    protected function init()
    {
    }

    public function prepare(string $id, array $params): static
    {
        $this->id = $id;
        $this->data = array_replace_recursive($this->data, $params);
        return $this;
    }

    public function bindChild(ViewComponentInterface $component): static
    {
        if ($component instanceof self) {
            $component->parent = $this;
        }
        return $this;
    }

    public function action(ViewAction $action): static
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws ViewException
     */
    public function __get(string $name): mixed
    {
        if (!$this->__isset($name)) {
            throw UndefinedPropertyException::forComponent("Access to undefined property '$name'", $this);
        }
        return $this->data[$name] ?? $this->parent?->$name ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]) || isset($this->parent?->$name);
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ViewException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->__isset($name) && is_callable($this->$name)) {
            return ($this->$name)(...$arguments);
        }

        throw UndefinedMethodException::forComponent("Call to undefined method '$name'", $this);
    }

    public function __debugInfo(): array
    {
        return [
            'name' => static::class,
        ];
    }
}
