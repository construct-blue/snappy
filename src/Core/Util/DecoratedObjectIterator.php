<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use IteratorAggregate;
use Traversable;

class DecoratedObjectIterator implements IteratorAggregate
{
    private $decorator;
    private iterable $iterator;

    /**
     * @param iterable<object> $iterator
     * @param callable $decorator
     */
    public function __construct(iterable $iterator, callable $decorator)
    {
        $this->iterator = $iterator;
        $this->decorator = $decorator;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->iterator as $item) {
            yield ($this->decorator)($item);
        }
    }
}
