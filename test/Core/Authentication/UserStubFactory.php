<?php

namespace BlueTest\Core\Authentication;

use Blue\Core\Authentication\User;
use ReflectionObject;

class UserStubFactory
{
    public static function createFromArray(array $userData): User
    {
        $user = new User();
        $reflection = new ReflectionObject($user);
        foreach ($userData as $key => $value) {
            $reflection->getProperty($key)->setValue($user, $value);
        }
        return $user;
    }
}