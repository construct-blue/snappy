<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\UndefinedMethodException;
use Blue\Core\View\Exception\UndefinedPropertyException;
use Blue\Core\View\Exception\ViewException;
use Closure;

use function array_replace_recursive;

#[Entrypoint(__DIR__ . '/ViewComponent.ts')]
abstract class ViewComponent implements ViewComponentInterface
{
    // phpcs:ignore
    private string $__id;
    // phpcs:ignore
    private array $__data = [];
    // phpcs:ignore
    private ViewAction $__action;
    // phpcs:ignore
    private ?ViewComponentInterface $__parent = null;

    final public function __construct()
    {
        $this->__action = new ViewAction('');
        $this->__init();
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
        $component->__data = array_replace_recursive($component->__data, $params);
        return $component;
    }

    public function __id(): string
    {
        return $this->__id;
    }

    public function action(): ViewAction
    {
        return $this->__action;
    }

    protected function __init()
    {
    }

    public function __prepare(string $id, array $params): static
    {
        $this->__id = $id;
        $this->__data = array_replace_recursive($this->__data, $params);
        return $this;
    }

    public function __bindParent(ViewComponentInterface $parent): static
    {
        $this->__parent = $parent;
        return $this;
    }

    public function __action(ViewAction $action): static
    {
        $this->__action = $action;
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
        return $this->__data[$name] ?? $this->__parent?->$name ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->__data[$name]) || isset($this->__parent?->$name);
    }

    public function __set(string $name, $value): void
    {
        $this->__data[$name] = $value;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ViewException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->__isset($name) && $this->$name instanceof Closure) {
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
