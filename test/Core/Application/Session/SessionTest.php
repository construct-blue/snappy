<?php

declare(strict_types=1);

namespace BlueTest\Core\Application\Session;

use Blue\Core\Application\Session\MessageType;
use Blue\Core\Application\Session\Session;
use Blue\Core\I18n\Language;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testShouldAutoSaveAndLoad()
    {
        $session = new Session();
        $id = $session->getId();
        $session->addMessage('test');

        unset($session);

        $session = new Session($id);
        $this->assertEquals(['info' => ['test']], $session->getMessages());
    }

    public function testShouldBeModifedWhenDataIsSet()
    {
        $session = new Session();
        $id = $session->getId();
        $this->assertFalse($session->isModified());
        $session->getToken();
        unset($session);
        $session = new Session($id);
        $this->assertTrue($session->isModified());
    }

    public function testLoggedIn()
    {
        $session = new Session();
        $this->assertFalse($session->isLoggedIn());
        $session->setUserId('user-id');
        $this->assertTrue($session->isLoggedIn());
        $session->setUserId(null);
        $this->assertFalse($session->isLoggedIn());
    }

    public function testShouldGenerateNewTokens()
    {
        $session = new Session();
        $id = $session->getId();
        $token = $session->getToken();
        $this->assertNotEmpty($token);
        unset($session);
        $session = new Session($id);
        $this->assertEquals($token, $session->getToken());
        $session->renewToken();
        $this->assertNotEquals($token, $session->getToken());
    }

    public function testDefaultLanguage()
    {
        $session = new Session();
        $id = $session->getId();

        $this->assertEquals(Language::DEFAULT, $session->getLanguage());
        $session->setLanguage(Language::GERMAN);
        unset($session);
        $session = new Session($id);
        $this->assertEquals(Language::GERMAN, $session->getLanguage());
    }

    public function testMessages()
    {
        $session = new Session();
        $id = $session->getId();
        $session->addMessage('message', MessageType::INFO);
        $this->assertTrue($session->isModified());
        unset($session);
        $session = new Session($id);
        $this->assertEquals([
            MessageType::INFO->value => ['message']
        ], $session->getMessages());
    }

    public function testValidations()
    {
        $session = new Session();
        $id = $session->getId();
        $session->addValidation('field', 'message');
        $this->assertTrue($session->isModified());
        unset($session);
        $session = new Session($id);
        $this->assertEquals([
            'field' => ['message']
        ], $session->getValidations());
    }
}
