<?php

declare(strict_types=1);

namespace Blue\Models\User;

use Blue\Core\Database\ObjectStorage;
use Blue\Core\Http\Status;
use Blue\Core\Util\SingletonTrait;
use Generator;

class UserRepository
{
    use SingletonTrait;

    protected ObjectStorage $storage;

    protected function onConstruct(): void
    {
        $this->storage = new ObjectStorage(User::class, 'default', 'user');
        if ($this->storage->isSetup()) {
            $systemUser = new User();
            $systemUser->setName(User::DEFAULT_NAME_SYSTEM);
            $systemUser->setType(UserType::HIDDEN);
            $this->save($systemUser);

            $guestUser = new User();
            $guestUser->setName(User::DEFAULT_NAME_GUEST);
            $guestUser->setType(UserType::HIDDEN);
            $this->save($guestUser);

            $adminUser = new User();
            $adminUser->setName(User::DEFAULT_NAME_ADMIN);
            $adminUser->setType(UserType::DEFAULT);
            $adminUser->setPasswordPlain(User::DEFAULT_NAME_ADMIN);
            $adminUser->setState(UserState::ACTIVE);
            $adminUser->setRoles([UserRole::ADMINISTRATOR]);
            $this->save($adminUser);
        }
    }

    public function findAll(bool $showHidden = false): Generator
    {
        foreach ($this->storage->loadAll() as $user) {
            if ($user->isEditable() || $showHidden) {
                yield $user;
            }
        }
    }

    public function findById(string $id): User
    {
        return $this->storage->loadById($id);
    }

    public function findByName(string $name): User
    {
        return $this->storage->loadByCode($name);
    }

    public function save(User $user): User
    {
        if ($this->existsByName($user->getName())) {
            if ($this->findByName($user->getName())->getId() != $user->getId()) {
                throw new UserException("User already exists", Status::VALIDATION_ERROR, null, 'name');
            }
        }
        $this->storage->save($user, $user->getId(), $user->getName());
        return $user;
    }

    public function delete(string $id): bool
    {
        return $this->storage->delete($id);
    }

    public function existsByName(string $name): bool
    {
        return $this->storage->existsByCode($name);
    }
}
