<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use Blue\Core\Util\Callback\{CallbackArray, CallbackBool, CallbackInterface, CallbackString};

use function is_callable;

function cstring(callable|string $callable): CallbackString
{
    if (!is_callable($callable)) {
        $callable = fn() => $callable;
    }
    return CallbackString::fromCallable($callable);
}

function cbool(callable|bool $callable): CallbackBool
{
    if (!is_callable($callable)) {
        $callable = fn() => $callable;
    }
    return CallbackBool::fromCallable($callable);
}

function carray(callable|array $callable): CallbackArray
{
    if (!is_callable($callable)) {
        $callable = fn() => $callable;
    }
    return CallbackArray::fromCallable($callable);
}

function cres(mixed $callback): mixed
{
    if ($callback instanceof CallbackInterface) {
        return $callback->resolve();
    }
    return $callback;
}
