<?php

declare(strict_types=1);

namespace Blue\Core\Queue;

use Closure;
use Blue\Core\Logger\Logger;
use Blue\Core\Util\SingletonTrait;
use SplQueue;
use Throwable;

class Queue
{
    use SingletonTrait;

    private Logger $logger;
    private SplQueue $deferredTasks;

    protected function onConstruct(): void
    {
        $this->logger = new Logger();
        $this->deferredTasks = new SplQueue();
    }

    public function deferTask(Closure $closure): self
    {
        $this->deferredTasks->enqueue($closure);
        return $this;
    }

    public function executeDeferredTasks(): void
    {
        /** @var Closure $deferredTask */
        foreach ($this->deferredTasks as $deferredTask) {
            try {
                $deferredTask();
            } catch (Throwable $throwable) {
                $this->logger->error($throwable);
            }
        }
    }
}
