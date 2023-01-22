<?php

declare(strict_types=1);

namespace BlueTest\Core\Application\Session;

use Blue\Core\Application\Session\SessionContainer;
use Blue\Models\User\User;
use PHPUnit\Framework\TestCase;

class SessionContainerTest extends TestCase
{
    public function testShouldCreateSessions()
    {
        $container = new SessionContainer();
        $session = $container->get(null);
        $this->assertNotEmpty($session->getId());
        $session->setUser(new User());
        $this->assertTrue($session->isLoggedIn());
        $id = $session->getId();
        unset($container);
        unset($session);
        $container = new SessionContainer();
        $session = $container->get($id);
        $this->assertTrue($session->isLoggedIn());
    }
}
