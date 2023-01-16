<?php

declare(strict_types=1);

namespace Blue\Core\Application\Emitter;

use Blue\Core\Queue\Queue;
use Psr\Http\Message\ResponseInterface;

class SapiStreamEmitter extends \Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter
{
    public function emit(ResponseInterface $response): bool
    {
        $result = parent::emit($response);
        fastcgi_finish_request();
        Queue::instance()->executeDeferredTasks();
        return $result;
    }
}
