<?php

declare(strict_types=1);

namespace BlueTest\Core\Database\Object;

class TestObject implements \JsonSerializable
{
    private string $code;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): TestObject
    {
        $this->code = $code;
        return $this;
    }

    public static function __set_state(array $an_array): object
    {
        return (new TestObject())
            ->setCode($an_array['code']);
    }



    public function jsonSerialize(): array
    {
        return [
            'code' => $this->getCode()
        ];
    }
}
