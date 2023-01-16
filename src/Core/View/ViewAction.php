<?php

namespace Blue\Core\View;

class ViewAction
{
    private string $code;

    private ?string $result = null;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function is(string $code): bool
    {
        return $code === $this->code;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): ViewAction
    {
        $this->result = $result;
        return $this;
    }
}
