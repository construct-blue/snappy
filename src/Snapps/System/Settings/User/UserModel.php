<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\View\ViewModel;
use Blue\Models\User\User;
use Blue\Models\User\UserRole;
use Blue\Models\User\UserState;
use Blue\Snapps\System\Settings\User\Exception\UserActionException;

class UserModel extends ViewModel
{
    private string $id;
    private bool $admin;
    private string $name;
    private bool $locked;
    private array $roles;
    private array $roleNames;
    private array $snapps;

    public static function initFromUser(User $user): UserModel
    {
        $model = new UserModel();

        $model->id = $user->getId();
        $model->admin = $user->isAdmin();
        $model->name = $user->getName();
        $model->locked = $user->getState()->is(UserState::LOCKED);
        $model->roles = array_keys(UserRole::list($user->getRoles()));
        $model->snapps = $user->getSnapps();
        $model->roleNames = array_map(fn(UserRole $role) => $role->getName(), $user->getRoles());
        return $model;
    }

    public static function initFromForm(array $formData): UserModel
    {
        $model = new UserModel();
        if (!isset($formData['id'])) {
            throw new UserActionException('User ID missing in form data');
        }

        $filterArgs = [
            'id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'roles' => [
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags' => FILTER_FORCE_ARRAY
            ],
            'snapps' => [
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags' => FILTER_FORCE_ARRAY
            ],
            'locked' => FILTER_VALIDATE_BOOLEAN
        ];

        $formData = filter_var_array($formData, $filterArgs);

        $model->id = $formData['id'];
        $model->name = $formData['name'];
        $model->roles = $formData['roles'] ?? [];
        $model->snapps = $formData['snapps'] ?? [];
        $model->locked = $formData['locked'];
        return $model;
    }

    public function updateUser(User $user): void
    {
        $user->setName($this->getName());
        $user->setRoles(UserRole::map($this->getRoles()));
        $user->setSnapps($this->getSnapps());

        if ($this->isLocked()) {
            $user->setState(UserState::LOCKED);
        } else {
            $user->setState(UserState::ACTIVE);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function getSnapps(): array
    {
        return $this->snapps;
    }

    /**
     * @return array
     */
    public function getRoleNames(): array
    {
        return $this->roleNames;
    }
}