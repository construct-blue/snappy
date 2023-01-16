<?php

namespace BlueTest\Core\Authentication;

use Blue\Core\Authentication\User;
use Blue\Core\Authentication\UserRepository;
use Blue\Core\Authentication\UserRole;
use Blue\Core\Authentication\UserState;
use Blue\Core\Authentication\UserType;
use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;

use function password_hash;

use const PASSWORD_DEFAULT;

class UserRepositoryStub extends UserRepository
{
    protected function onConstruct(): void
    {
        $connection = Connection::temp();
        $this->storage = new ObjectStorage(User::class, 'default', 'user', $connection);
        $adminUser = new User();
        $adminUser->setName(User::DEFAULT_NAME_ADMIN);
        $adminUser->setType(UserType::DEFAULT);
        $adminUser->setPasswordPlain(User::DEFAULT_NAME_ADMIN);
        $adminUser->setState(UserState::ACTIVE);
        $adminUser->setRoles([UserRole::ADMIN]);
        $this->save($adminUser);
    }
}
