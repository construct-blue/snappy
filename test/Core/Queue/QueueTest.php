<?php

declare(strict_types=1);

namespace BlueTest\Core\Queue;

use Exception;
use Blue\Core\Queue\Queue;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    public function testShouldExecuteDeferredTasksInAddingOrder()
    {
        $executedTasks = [];
        Queue::instance()->deferTask(function () use (&$executedTasks) {
            $executedTasks[] = 'task1';
        });
        Queue::instance()->deferTask(function () {
            throw new Exception('');
        });
        Queue::instance()->deferTask(function () use (&$executedTasks) {
            $executedTasks[] = 'task2';
        });
        Queue::instance()->executeDeferredTasks();

        $this->assertEquals(['task1', 'task2'], $executedTasks);
    }
}
