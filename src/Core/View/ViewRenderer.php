<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\Environment\Environment;
use Blue\Core\Util\PlaceholderHelper;
use Blue\Core\View\Exception\InfiniteRecursionException;
use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\Exception\ViewException;
use Blue\Core\View\Helper\Functional;
use Blue\Core\View\Helper\Document;
use Blue\Core\View\Helper\Body;
use Closure;
use Throwable;

use function class_exists;
use function get_debug_type;
use function is_array;
use function is_string;

class ViewRenderer
{
    public const MAX_COMPONENT_LEVEL = 1000;
    private ClientResources $resources;

    final public function __construct(
        private readonly ?ViewLogger $logger = null,
        private readonly bool $debug = false,
    ) {
        $this->resources = new ClientResources(Environment::instance());
    }

    /**
     * @param ViewComponentInterface $component
     * @param array|null $params
     * @param ViewComponentInterface|null $parent
     * @return string
     * @throws InvalidComponentClassException
     * @throws InvalidComponentContentException
     * @throws InvalidComponentParameterException
     * @throws ViewException
     */
    public function render(
        ViewComponentInterface $component,
        array $params = null,
        ViewComponentInterface $parent = null
    ): string {
        $component = $this->prepareComponent($component, $params, $parent);
        return $this->renderComponent($component);
    }

    /**
     * @param ViewComponentInterface $component
     * @return string
     * @throws InvalidComponentClassException
     * @throws InvalidComponentContentException
     * @throws InvalidComponentParameterException
     * @throws ViewException
     */
    private function renderComponent(ViewComponentInterface $component): string
    {
        try {
            $content = $this->prepareContent($component->render(), $component, true);
            return $this->renderHtml($content);
        } catch (ViewException $exception) {
            $this->logger?->error($exception);
            if ($this->debug) {
                throw $exception;
            }
        } catch (Throwable $throwable) {
            $this->logger?->error($throwable);
            if ($this->debug) {
                throw ViewException::from($throwable);
            }
        }
        return '';
    }

    /**
     * @param ViewComponentInterface $component
     * @param array|null $params
     * @param ViewComponentInterface|null $parent
     * @param int $index
     * @return ViewComponentInterface
     * @throws ViewException
     */
    private function prepareComponent(
        ViewComponentInterface $component,
        array $params = null,
        ViewComponentInterface $parent = null,
        int $index = 1
    ): ViewComponentInterface {
        try {
            $this->resources->importComponent($component);

            if ($component instanceof Document) {
                $component->setResources($this->resources);
            }

            if (null === $parent) {
                $id = 'c-' . $index;
            } else {
                $id = $parent->getId() . '-' . $index;
            }

            $component->prepare($id, $params ?? [], $parent);
        } catch (Throwable $throwable) {
            $this->logger?->error($throwable);
            if ($this->debug) {
                throw ViewException::from($throwable);
            }
        }

        return $component;
    }

    /**
     * @param class-string<ViewComponentInterface> $className
     * @return ViewComponentInterface
     * @throws InvalidComponentClassException
     */
    public static function instantiateComponent(string $className): ViewComponentInterface
    {
        if (!in_array(ViewComponentInterface::class, class_implements($className))) {
            throw new InvalidComponentClassException(
                "Component class $className must implement " . ViewComponentInterface::class
            );
        }
        return $className::new();
    }

    /**
     * @param array $content
     * @param ViewComponentInterface|null $parent
     * @param bool $render
     * @param int $index
     * @return array
     * @throws InvalidComponentClassException
     * @throws InvalidComponentContentException
     * @throws InvalidComponentParameterException
     * @throws ViewException
     */
    private function prepareContent(
        array $content,
        ViewComponentInterface $parent = null,
        bool $render = false,
        int $index = 1,
    ): array {
        $result = [];
        $contentCount = count($content);
        foreach ($content as $key => $item) {
            if (is_string($key) && str_contains($key, '\\') && class_exists($key)) {
                if (!is_array($item)) {
                    throw InvalidComponentParameterException::forComponent(
                        'Component params must be assoc array',
                        $parent
                    );
                }
                $result[$contentCount + $index] = $this->prepareComponent(
                    self::instantiateComponent($key),
                    $item,
                    $parent,
                    $index
                );
            } elseif (is_string($item)) {
                if (str_contains($item, '\\') && class_exists($item)) {
                    throw InvalidComponentContentException::forComponent(
                        'Classes must not be used as content',
                        $parent
                    );
                }
                $result[$key] = $item;
            } elseif (is_array($item)) {
                try {
                    $result[$key] = $this->prepareContent($item, $parent, $render, $index);
                } catch (ViewException $exception) {
                    $this->logger?->error($exception);
                    if ($this->debug) {
                        throw $exception;
                    }
                }
            } elseif ($item instanceof Closure) {
                $result[$key] = $this->prepareComponent(
                    Functional::include($item),
                    null,
                    $parent,
                    $index
                );
            } elseif ($render && $item instanceof Body) {
                $result[$key] = $this->render($item, null, $parent);
            } elseif ($item instanceof ViewComponentInterface) {
                $result[$key] = $this->prepareComponent(
                    $item,
                    null,
                    $parent,
                    $index
                );
            } else {
                throw InvalidComponentContentException::forComponent(
                    'Content of type ' . get_debug_type($item) . ' not suppoerted',
                    $parent
                );
            }
            $index++;
        }
        return $result;
    }

    /**
     * @param array $content
     * @return string
     * @throws InvalidComponentClassException
     * @throws InvalidComponentContentException
     * @throws InvalidComponentParameterException
     * @throws ViewException
     */
    private function renderHtml(array $content): string
    {
        ob_start();
        try {
            $this->echoHtml($content);
        } finally {
            $html = ob_get_clean();
        }
        return $html;
    }

    /**
     * @param array $content
     * @throws InvalidComponentClassException
     * @throws InvalidComponentContentException
     * @throws InvalidComponentParameterException
     * @throws ViewException
     */
    private function echoHtml(array $content): void
    {
        foreach ($content as $key => $item) {
            $hasTag = !is_int($key);
            if ($hasTag) {
                echo '<';
                echo $key;
                echo '>';
            }
            if (is_string($item)) {
                echo $item;
            } elseif (is_array($item)) {
                $this->echoHtml($item);
            } elseif ($item instanceof ViewComponentInterface) {
                if (ob_get_level() > self::MAX_COMPONENT_LEVEL) {
                    throw InfiniteRecursionException::forComponent('Maximum nesting reached', $item);
                }
                echo $this->renderComponent($item);
            }
            if ($hasTag) {
                $i = 0;
                echo '</';
                while (isset($key[$i]) && $key[$i] !== ' ') {
                    echo $key[$i++];
                }
                echo '>';
            }
        }
    }
}
