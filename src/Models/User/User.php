<?php

declare(strict_types=1);

namespace Blue\Models\User;

use JsonSerializable;

use function password_hash;
use function password_verify;
use function strlen;

use const PASSWORD_DEFAULT;

final class User implements JsonSerializable
{
    public const MIN_PASSWORD_LENGTH = 5;

    public const DEFAULT_NAME_ADMIN = 'admin';
    public const DEFAULT_NAME_SYSTEM = 'system';
    public const DEFAULT_NAME_GUEST = 'guest';

    private string $id;

    private ?string $name = null;
    private ?string $passwordHash = null;

    private UserState $state = UserState::LOCKED;
    private UserType $type = UserType::DEFAULT;

    /** @var UserRole[] */
    private array $roles = [];

    /** @var UserPermission[] */
    private array $permissions;

    /** @var UserOption[] */
    private array $options = [];

    private array $snapps = [];

    public function __construct()
    {
        $this->id = uniqid();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getState(): UserState
    {
        return $this->state;
    }

    public function getType(): UserType
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setType(UserType $type): User
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @throws UserException
     */
    public function setName(?string $name): User
    {
        if ($this->isAdmin()) {
            throw UserException::forValidation('name', 'Admin users name can\'t be changed.');
        }
        $this->name = $name;
        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(?string $hash): User
    {
        $this->passwordHash = $hash;
        return $this;
    }

    public function setPasswordPlain(string $password): User
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw UserException::forValidation(
                'password',
                'Password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters long.',
            );
        }
        return $this->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
    }

    public function verifyPassword(string $password): bool
    {
        if (empty($this->getPasswordHash())) {
            return false;
        }
        return password_verify($password, $this->getPasswordHash());
    }

    public function setState(UserState $state): User
    {
        if (UserState::ACTIVE === $state && (empty($this->getName()) || empty($this->getPasswordHash()))) {
            throw UserException::forValidation('state', 'User without name and hash can\'t be unlocked.');
        }
        if (UserState::ACTIVE !== $state && $this->isAdmin()) {
            throw UserException::forValidation('state', 'Admin user can\'t be locked or deleted.');
        }
        $this->state = $state;
        return $this;
    }

    public function isActive(): bool
    {
        return !empty($this->getName()) && !empty($this->getPasswordHash()) && $this->getState() === UserState::ACTIVE;
    }

    public function isEditable(): bool
    {
        return $this->getType() !== UserType::HIDDEN;
    }

    public function isGuest(): bool
    {
        return $this->getName() === self::DEFAULT_NAME_GUEST;
    }

    public function isSystem(): bool
    {
        return $this->getName() === self::DEFAULT_NAME_SYSTEM;
    }

    public function isAdmin(): bool
    {
        return $this->getName() === self::DEFAULT_NAME_ADMIN;
    }

    /**
     * @return UserRole[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param UserRole[] $roles
     * @return User
     * @throws UserException
     */
    public function setRoles(array $roles): User
    {
        if ($this->isAdmin() && $roles != [UserRole::ADMINISTRATOR]) {
            throw UserException::forValidation('roles', 'Admin users roles can\'t be changed.');
        }
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return UserOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param UserOption[] $options
     * @return User
     */
    public function setOptions(array $options): User
    {
        $this->options = $options;
        return $this;
    }

    public function hasOption(UserOption $option): bool
    {
        return in_array($option, $this->options);
    }

    public function addOption(UserOption $option): User
    {
        if (!$this->hasOption($option)) {
            $this->options[] = $option;
        }
        return $this;
    }

    public function isModified(): bool
    {
        return !$this->isGuest() || !empty($this->options);
    }

    public function getSnapps(): array
    {
        return $this->snapps;
    }

    public function setSnapps(array $snapps): User
    {
        $this->snapps = $snapps;
        return $this;
    }

    public function getPermissions(): array
    {
        if (!isset($this->permissions)) {
            $this->permissions = [];
            foreach ($this->getRoles() as $role) {
                foreach ($role->permissions() as $permission) {
                    if (!in_array($permission, $this->permissions)) {
                        $this->permissions[] = $permission;
                    }
                }
            }
        }
        return $this->permissions;
    }

    public function hasPermission(UserPermission $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function hasSnapp(string $snappCode): bool
    {
        if ($this->hasPermission(UserPermission::ALL_SNAPPS)) {
            return true;
        }
        return in_array($snappCode, $this->getSnapps());
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'state' => $this->getState(),
            'pwHash' => $this->getPasswordHash(),
            'roles' => $this->getRoles(),
            'options' => $this->getOptions(),
            'snapps' => $this->getSnapps(),
        ];
    }

    public static function __set_state(array $an_array): User
    {
        $user = new User();
        $user->id = $an_array['id'];
        $user->name = $an_array['name'];
        $user->passwordHash = $an_array['pwHash'];
        $user->type = UserType::from($an_array['type']);
        $user->state = UserState::from($an_array['state']);
        $user->roles = UserRole::map($an_array['roles']);
        $user->options = UserOption::map($an_array['options']);
        $user->snapps = $an_array['snapps'] ?? [];
        return $user;
    }
}