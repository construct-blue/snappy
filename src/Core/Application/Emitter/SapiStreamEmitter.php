<?php

declare(strict_types=1);

namespace Blue\Core\Application\Emitter;

use Blue\Core\Queue\Queue;
use Psr\Http\Message\ResponseInterface;

class SapiStreamEmitter extends \Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter
{
    private float $initTime;

    public function __construct(int $maxBufferLength = 8192)
    {
        $this->initTime = microtime(true);
        parent::__construct($maxBufferLength);
    }

    public function emit(ResponseInterface $response): bool
    {
        $emitTime = microtime(true);
        $result = parent::emit($response);

        if (str_contains($response->getHeaderLine('Content-Type'), 'text/html')) {
            $endTime = microtime(true);
            $processTime = round(($endTime - $this->initTime) * 1000);
            $renderTime = round(($endTime - $emitTime) * 1000);
            $memoryPeak = round(memory_get_peak_usage(true) / 1000000, 2);
            echo <<<JS
<script>
  console.table({
    "processTime": "$processTime ms",
    "renderTime": "$renderTime ms",
    "memoryPeak": "$memoryPeak MB"
  });
</script>
JS;
        }

        fastcgi_finish_request();
        Queue::instance()->executeDeferredTasks();
        return $result;
    }
}
