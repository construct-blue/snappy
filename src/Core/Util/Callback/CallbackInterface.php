<?php

namespace Blue\Core\Util\Callback;

use JsonSerializable;

interface CallbackInterface extends JsonSerializable
{
    public function resolve();
    public function __invoke();
}
