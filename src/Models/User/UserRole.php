<?php

declare(strict_types=1);

namespace Blue\Models\User;

enum UserRole: string
{
    case ADMINISTRATOR = 'administrator';
    case CONTENT_MANAGER = 'content_manager';

    public static function compare(UserRole $a, UserRole $b): int
    {
        return $a->value <=> $b->value;
    }

    public static function list(array $cases = null): array
    {
        $result = [];
        $cases = $cases ?? UserRole::cases();
        foreach ($cases as $case) {
            $result[$case->value] = $case->getName();
        }
        return $result;
    }

    public function getName(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    public static function map(array $values): array
    {
        return array_map(fn(string $value) => UserRole::from($value), $values);
    }

    /**
     * @return UserPermission[]
     */
    public function permissions(): array
    {
        if ($this === self::ADMINISTRATOR) {
            return [
                UserPermission::ALL_SNAPPS,
                UserPermission::CMS,
                UserPermission::ANALYTICS,
                UserPermission::SETTINGS,
                UserPermission::ACCOUNT,
            ];
        }
        if ($this === self::CONTENT_MANAGER) {
            return [
                UserPermission::CMS,
                UserPermission::ANALYTICS,
                UserPermission::ACCOUNT,
            ];
        }
        return [];
    }

    public function hasPermission(UserPermission $permission): bool
    {
        return in_array($permission, $this->permissions());
    }
}
