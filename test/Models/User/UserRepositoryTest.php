<?php
declare(strict_types=1);

namespace BlueTest\Models\User;

use Blue\Models\User\User;
use Blue\Models\User\UserOption;
use Blue\Models\User\UserRepository;
use Blue\Models\User\UserRole;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testShouldAutoCreateGuestUser()
    {
        $guest = UserRepository::instance()->findByName(User::DEFAULT_NAME_GUEST);
        $this->assertEquals(User::DEFAULT_NAME_GUEST, $guest->getName());
    }

    public function testShouldSaveNewUser()
    {
        $user = new User();
        $user->setName('test');
        $user->setOptions(UserOption::cases());
        $user->setRoles(UserRole::cases());
        UserRepository::instance()->save($user);
        $user = UserRepository::instance()->findByName('test');
        $this->assertEquals('test', $user->getName());
        $this->assertEquals(UserOption::cases(), $user->getOptions());
        $this->assertEquals(UserRole::cases(), $user->getRoles());
        $this->assertEmpty($user->getPasswordHash());
    }
}
