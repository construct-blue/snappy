<?php

namespace BlueTest\Core\Authentication;

use Blue\Core\Authentication\User;
use Blue\Core\Authentication\UserOption;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testAddOption()
    {
        $user = new User();
        $this->assertFalse($user->hasOption(UserOption::ALLOW_TRACKING));
        $user->addOption(UserOption::ALLOW_TRACKING);
        $this->assertTrue($user->hasOption(UserOption::ALLOW_TRACKING));
    }
}
