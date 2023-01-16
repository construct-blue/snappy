<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DEFAULT = 'default';

    public static function compare(UserRole $a, UserRole $b): int
    {
        return $a->value <=> $b->value;
    }

    public static function list(array $cases = null): array
    {
        $list = array_map(fn(UserRole $state) => $state->value, $cases ?? UserRole::cases());
        return array_combine($list, array_map('ucfirst', $list));
    }

    public static function map(array $values): array
    {
        return array_map(fn(string $value) => UserRole::from($value), $values);
    }
}
