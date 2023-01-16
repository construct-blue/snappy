<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

use JsonSerializable;
use Blue\Core\Application\Session\Window\Window;
use Blue\Core\Authentication\User;
use Blue\Core\Database\Connection;
use Blue\Core\Database\Exception\DatabaseException;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\I18n\Language;
use Blue\Core\Logger\Logger;
use Psr\Http\Server\MiddlewareInterface;

use function uniqid;

class Session implements JsonSerializable
{
    public const COOKIE_NAME = 'sid';
    private const ATTR_TOKEN = 'tkn';
    private const ATTR_LANGUAGE = 'lng';
    private const ATTR_USER_ID = 'uid';
    private const ATTR_MESSAGES = 'msg';

    private string $id;
    private string $token;
    private Language $language;
    private array $windows = [];
    private ?User $user = null;
    private array $messages = [];

    private ObjectStorage $sessionRepo;
    private ObjectStorage $userRepo;

    public function __construct(string $id = null)
    {
        $this->id = $id ?: uniqid('s-');
        if ($id) {
            $this->loadFromId($id);
        }
    }

    public function __destruct()
    {
        $this->saveWhenModified();
    }

    public function jsonSerialize(): array
    {
        return [
            self::ATTR_TOKEN => $this->getToken(),
            self::ATTR_LANGUAGE => $this->getLanguage()->value,
            self::ATTR_USER_ID => $this->getUser()?->getId(),
            self::ATTR_MESSAGES => $this->getMessages()
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language ?? Language::DEFAULT;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        if (!isset($this->token)) {
            $this->renewToken();
        }
        return $this->token;
    }

    public function renewToken()
    {
        $this->token = uniqid('t-');
    }

    /**
     * @return Window[]
     */
    public function getWindows(): array
    {
        return $this->windows;
    }

    public function openWindow(MiddlewareInterface $application): Window
    {
        $window = new Window($this, $application);
        $this->windows[$window->getId()] = $window;
        return $window;
    }

    public function closeWindow(string $id)
    {
        unset($this->windows[$id]);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Session
    {
        $this->user = $user;
        return $this;
    }

    public function addMessage(string $message)
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function resetMessages(): void
    {
        $this->messages = [];
    }

    private function getRepo(): ObjectStorage
    {
        if (!isset($this->sessionRepo)) {
            $this->sessionRepo = new ObjectStorage(Session::class, 'session', 'object', Connection::session());
        }
        return $this->sessionRepo;
    }

    private function getUserRepo(): ObjectStorage
    {
        if (!isset($this->userRepo)) {
            $this->userRepo = new ObjectStorage(User::class, 'user', 'user', Connection::session());
        }
        return $this->userRepo;
    }

    private function loadFromId(string $id): void
    {
        if ($this->getRepo()->existsById($id)) {
            $data = (array)$this->getRepo()->loadById($id);
            if ($data) {
                if (!empty($data[self::ATTR_LANGUAGE])) {
                    $this->language = Language::from($data[self::ATTR_LANGUAGE]);
                }
                if (!empty($data[self::ATTR_TOKEN])) {
                    $this->token = $data[self::ATTR_TOKEN];
                }
                if (!empty($data[self::ATTR_MESSAGES])) {
                    $this->messages = $data[self::ATTR_MESSAGES];
                }
                if (!empty($data[self::ATTR_USER_ID])) {
                    try {
                        $this->user = $this->getUserRepo()->loadById($data[self::ATTR_USER_ID]);
                    } catch (DatabaseException $exception) {
                        (new Logger())->error($exception);
                    }
                }
            }
        }
    }

    private function saveWhenModified(): void
    {
        if ($this->isModified()) {
            $this->getRepo()->save($this, $this->getId(), null);
            if ($this->getUser()) {
                $this->getUserRepo()->save($this->getUser(), $this->getUser()->getId(), $this->getUser()->getName());
            }
        }
    }

    public function isModified(): bool
    {
        return isset($this->token)
            || isset($this->language)
            || ($this->getUser() && $this->getUser()->isModified())
            || !empty($this->messages);
    }
}
