<?php

declare(strict_types=1);

namespace Blue\Core\Util;

trait BackedEnumTrait
{
    public function is(self $case): bool
    {
        return $this === $case;
    }

    public static function list(): array
    {
        $list = array_map(fn(self $case) => $case->value, self::cases());
        return array_combine($list, array_map('ucfirst', $list));
    }

    public static function map(array $values): array
    {
        return array_map(fn(string $value) => self::from($value), $values);
    }
}
