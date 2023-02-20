<?php

declare(strict_types=1);

namespace BlueTest\Snapps\System\Settings\User;

use Blue\Models\User\UserRole;
use Blue\Snapps\System\Settings\User\UserModel;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function testInitFromForm()
    {
        $formData = [
            'id' => 'user-id',
            'name' => 'testuser<script>console.log(window)</script>',
            'roles' => [
                UserRole::ADMINISTRATOR->value
            ],
            'snapps' => [
                'test-snapp'
            ],
            'locked' => '1'
        ];

        $model = UserModel::initFromForm($formData);
        $this->assertEquals('user-id', $model->getId());
        $this->assertEquals('testuser&lt;script&gt;console.log(window)&lt;/script&gt;', $model->getName());
        $this->assertEquals([UserRole::ADMINISTRATOR->value], $model->getRoles());
        $this->assertEquals(['test-snapp'], $model->getSnapps());
        $this->assertTrue($model->isLocked());
    }
}
