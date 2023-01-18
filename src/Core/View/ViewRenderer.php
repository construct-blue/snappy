<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\InfiniteRecursionException;
use Closure;
use Blue\Core\Util\Placeholder\PlaceholderHelper;
use Blue\Core\View\Exception\InvalidComponentClassException;
use Blue\Core\View\Exception\InvalidComponentContentException;
use Blue\Core\View\Exception\InvalidComponentParameterException;
use Blue\Core\View\Exception\ViewException;
use Throwable;

use function class_exists;
use function explode;
use function get_debug_type;
use function is_array;
use function is_string;

class ViewRenderer
{
    private EntrypointHelper $entrypointHelper;

    final public function __construct(
        private readonly ?ViewLogger $logger = null,
        private readonly bool $debug = false
    ) {
        $this->entrypointHelper = new EntrypointHelper();
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
            return PlaceholderHelper::replacePlaceholder($this->renderContent($content), $component);
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
            $this->entrypointHelper->enableComponent($component);

            if ($parent) {
                $id = $parent->__id() . '-' . $index;
                $component->__bindParent($parent);
            } else {
                $id = 'c-' . $index;
            }

            $component->__prepare($id, $params ?? []);

            if ($parent && strlen($component->__id()) > 1000) {
                throw InfiniteRecursionException::forComponent('Maximum nesting reached', $parent);
            }

            if ($component instanceof PageWrapper) {
                $component->styles = $this->entrypointHelper->dumpCss(...);
                $component->scripts = $this->entrypointHelper->dumpJs(...);
            }
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

        return $component;
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
        foreach ($content as $key => $item) {
            if (is_string($key) && class_exists($key)) {
                if (!is_array($item)) {
                    throw InvalidComponentParameterException::forComponent(
                        'Component params must be assoc array',
                        $parent
                    );
                }
                $result[count($content) + $index] = $this->prepareComponent(
                    ViewComponent::fromClassName($key),
                    $item,
                    $parent,
                    $index
                );
            } elseif (is_array($item)) {
                try {
                    $result[$key] = $this->prepareContent($item, $parent, $render, $index);
                } catch (ViewException $exception) {
                    $this->logger?->error($exception);
                    if ($this->debug) {
                        throw $exception;
                    }
                }
            } elseif (is_string($item)) {
                if (class_exists($item)) {
                    throw InvalidComponentContentException::forComponent(
                        'Classes must not be used as content',
                        $parent
                    );
                }
                $result[$key] = $item;
            } elseif ($item instanceof Closure) {
                $result[$key] = $this->prepareComponent(
                    ClosureView::from($item),
                    null,
                    $parent,
                    $index
                );
            } elseif ($render && $item instanceof PriorityViewComponent) {
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
     */
    private function renderContent(array $content): string
    {
        $result = '';
        foreach ($content as $key => $item) {
            if (is_array($item)) {
                $result .= $this->handleHtmlTag($this->renderContent($item), $key);
            } elseif (is_string($item)) {
                $result .= $this->handleHtmlTag($item, $key);
            } elseif ($item instanceof ViewComponentInterface) {
                $result .= $this->handleHtmlTag($this->renderComponent($item), $key);
            }
        }
        return $result;
    }

    private function handleHtmlTag(string $content, string|int $tagOrKey): string
    {
        if (is_string($tagOrKey) && !empty($tagOrKey)) {
            return $this->wrapInHtmlTag($content, $tagOrKey);
        }
        return $content;
    }

    private function wrapInHtmlTag(string $content, string $tag): string
    {
        $openTag = "<$tag>";
        [$closingTag] = explode(' ', $tag);
        $closingTag = "</$closingTag>";
        return $openTag . $content . $closingTag;
    }

    public function action(ViewComponentInterface $component, ViewAction $action, array $params = null): void
    {
        $component = $this->prepareComponent($component, $params);
        $this->actionComponent($component, $action);
    }

    private function actionComponent(ViewComponentInterface $component, ViewAction $action): void
    {
        $content = $this->prepareContent($component->__action($action)->render(), $component, false);
        $this->actionContent($content, $action);
    }

    private function actionContent(array $content, ViewAction $action): void
    {
        foreach ($content as $item) {
            if (is_array($item)) {
                $this->actionContent($item, $action);
            } elseif ($item instanceof ViewComponentInterface) {
                $this->actionComponent($item, $action);
            }
        }
    }
}
