<?php

declare(strict_types=1);

namespace BlueTest\Core\Database;

use Blue\Core\Database\Storable;

class TestObject implements Storable
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

    public static function fromStorage(array $data): static
    {
        return (new TestObject())
            ->setCode($data['code']);
    }



    public function toStorage(): array
    {
        return [
            'code' => $this->getCode()
        ];
    }
}
