<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

use Blue\Core\Database\Connection;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StdClassSerializer;
use Blue\Core\I18n\Language;

use function uniqid;

class Session
{
    public const COOKIE_NAME = 'sid';
    private const ATTR_TOKEN = 'tkn';
    private const ATTR_LANGUAGE = 'lng';
    private const ATTR_USER_ID = 'uid';
    private const ATTR_MESSAGES = 'msg';
    private const ATTR_VALIDATIONS = 'vld';

    private string $id;
    private string $token;
    private ?string $userId = null;
    private Language $language;
    private array $messages = [];
    private array $validations = [];

    private ObjectStorage $storage;

    public function __construct(string $id = null)
    {
        $this->init($id);
    }

    private function init(string $id = null): void
    {
        if (null === $id) {
            $this->generateId();
        } else {
            $this->loadFromId($id);
        }
    }

    public function __destruct()
    {
        $this->saveWhenModified();
    }

    public function toStorage(): array
    {
        return [
            self::ATTR_TOKEN => $this->getToken(),
            self::ATTR_LANGUAGE => $this->getLanguage()->value,
            self::ATTR_USER_ID => $this->getUserId(),
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

    public function getCookie(): string
    {
        return Session::COOKIE_NAME . '=' . $this->getId() . '; Path=/';
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

    public function isLoggedIn(): bool
    {
        return null !== $this->getUserId();
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     * @return Session
     */
    public function setUserId(?string $userId): Session
    {
        $this->userId = $userId;
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

    private function getStorage(): ObjectStorage
    {
        if (!isset($this->storage)) {
            $this->storage = new ObjectStorage(new StdClassSerializer(), 'session', 'object', Connection::session());
        }
        return $this->storage;
    }

    private function generateId(): void
    {
        $this->id = uniqid('s-');
    }

    private function loadFromId(string $id): void
    {
        if ($this->getStorage()->existsById($id)) {
            $this->id = $id;
            $data = (array)$this->getStorage()->loadById($id);
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
                $this->userId = $data[self::ATTR_USER_ID];
            }
        } else {
            $this->init();
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
        $this->getStorage()->save((object)$this->toStorage(), $this->getId(), null);
    }

    public function isModified(): bool
    {
        return !empty($this->token)
            || !empty($this->language)
            || !empty($this->userId)
            || !empty($this->messages)
            || !empty($this->validations);
    }
}
