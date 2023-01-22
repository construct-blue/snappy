<?php

declare(strict_types=1);

namespace BlueTest\Core\Application\Session;

use Blue\Core\Application\Session\MessageType;
use Blue\Core\Application\Session\Session;
use Blue\Core\I18n\Language;
use Blue\Models\User\User;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testShouldAutoSaveAndLoad()
    {
        $session = new Session(__FUNCTION__);

        $session->setUser(new User());
        $session->getUser()->setName('test');

        unset($session);

        $session = new Session(__FUNCTION__);
        $this->assertEquals('test', $session->getUser()->getName());
    }

    public function testShouldBeModifedWhenDataIsSet()
    {
        $session = new Session(__FUNCTION__);
        $this->assertFalse($session->isModified());
        $session->getToken();
        unset($session);
        $session = new Session(__FUNCTION__);
        $this->assertTrue($session->isModified());
    }

    public function testLoggedIn()
    {
        $session = new Session(__FUNCTION__);
        $this->assertFalse($session->isLoggedIn());
        $session->setUser(new User());
        $this->assertTrue($session->isLoggedIn());
        $session->getUser()->setName(User::DEFAULT_NAME_GUEST);
        $this->assertFalse($session->isLoggedIn());
    }

    public function testShouldGenerateNewTokens()
    {
        $session = new Session(__FUNCTION__);
        $token = $session->getToken();
        $this->assertNotEmpty($token);
        unset($session);
        $session = new Session(__FUNCTION__);
        $this->assertEquals($token, $session->getToken());
        $session->renewToken();
        $this->assertNotEquals($token, $session->getToken());
    }

    public function testDefaultLanguage()
    {
        $session = new Session(__FUNCTION__);
        $this->assertEquals(Language::DEFAULT, $session->getLanguage());
        $session->setLanguage(Language::GERMAN);
        unset($session);
        $session = new Session(__FUNCTION__);
        $this->assertEquals(Language::GERMAN, $session->getLanguage());
    }

    public function testMessages()
    {
        $session = new Session(__FUNCTION__);
        $session->addMessage('message', MessageType::INFO);
        $this->assertTrue($session->isModified());
        unset($session);
        $session = new Session(__FUNCTION__);
        $this->assertEquals([
            MessageType::INFO->value => ['message']
        ], $session->getMessages());
    }

    public function testValidations()
    {
        $session = new Session(__FUNCTION__);
        $session->addValidation('field', 'message');
        $this->assertTrue($session->isModified());
        unset($session);
        $session = new Session(__FUNCTION__);
        $this->assertEquals([
            'field' => ['message']
        ], $session->getValidations());
    }
}
