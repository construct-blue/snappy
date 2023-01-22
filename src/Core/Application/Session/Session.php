<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

use Blue\Core\Database\Connection;
use Blue\Core\Database\Exception\DatabaseException;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\I18n\Language;
use Blue\Core\Logger\Logger;
use Blue\Models\User\User;
use JsonSerializable;

use function uniqid;

class Session implements JsonSerializable
{
    public const COOKIE_NAME = 'sid';
    private const ATTR_TOKEN = 'tkn';
    private const ATTR_LANGUAGE = 'lng';
    private const ATTR_USER_ID = 'uid';
    private const ATTR_MESSAGES = 'msg';
    private const ATTR_VALIDATIONS = 'vld';

    private string $id;
    private string $token;
    private Language $language;
    private ?User $user = null;
    private array $messages = [];
    private array $validations = [];

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
            self::ATTR_MESSAGES => $this->getMessages(),
            self::ATTR_VALIDATIONS => $this->getValidations(),
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

    public function setLanguage(Language $language): Session
    {
        $this->language = $language;
        return $this;
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

    public function checkToken(string $token): bool
    {
        return $this->getToken() === $token;
    }

    public function renewToken(): string
    {
        return $this->token = uniqid('t-');
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isLoggedIn(): bool
    {
        if (null === $this->getUser()) {
            return false;
        }
        return !$this->getUser()->isGuest();
    }

    public function setUser(?User $user): Session
    {
        $this->user = $user;
        return $this;
    }

    public function addMessage(string $message, MessageType $type = MessageType::INFO)
    {
        $this->messages[$type->value][] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function popMessages(): array
    {
        $messages = $this->getMessages();
        $this->resetMessages();
        return $messages;
    }

    public function resetMessages(): self
    {
        $this->messages = [];
        return $this;
    }

    public function addValidation(string $field, string $message): self
    {
        $this->validations[$field][] = $message;
        return $this;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function popValidations(): array
    {
        $validations = $this->getValidations();
        $this->resetValidations();
        return $validations;
    }

    public function resetValidations(): self
    {
        $this->validations = [];
        return $this;
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
                    $this->messages = (array)$data[self::ATTR_MESSAGES];
                }
                if (!empty($data[self::ATTR_VALIDATIONS])) {
                    $this->validations = (array)$data[self::ATTR_VALIDATIONS];
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
            $this->save();
        }
    }

    public function save(): void
    {
        $this->getRepo()->save($this, $this->getId(), null);
        if ($this->getUser()) {
            $this->getUserRepo()->save($this->getUser(), $this->getUser()->getId(), $this->getUser()->getName());
        }
    }

    public function isModified(): bool
    {
        return isset($this->token)
            || isset($this->language)
            || ($this->getUser() && $this->getUser()->isModified())
            || !empty($this->messages)
            || !empty($this->validations);
    }
}
