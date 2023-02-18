<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\ViewModel;
use PHPUnit\Framework\TestCase;

class ViewModelTest extends TestCase
{
    public function testShouldReturnNullForNonExistingValues()
    {
        $model = new ViewModel([]);
        $this->assertNull($model->get('value'));
    }

    public function testShouldReturnExistingValues()
    {
        $model = new ViewModel(['value' => 'test']);
        $this->assertEquals('test', $model->get('value'));
    }

    public function testShouldReturnDefaultWhenValueIsNull()
    {
        $model = new ViewModel([]);
        $model->setDefault('value', 'default');
        $this->assertEquals('default', $model->get('value'));
        $model->set('value', 'test');
        $this->assertEquals('test', $model->get('value'));
        $model->set('value', null);
        $this->assertEquals('default', $model->get('value'));
    }

    public function testShoulReplaceValuesWhenMerged()
    {
        $model = new ViewModel([
            'value1' => 'test1',
            'map1' => [
                'item1' => 'itemValue1',
                'item2' => 'itemValue2'
            ]
        ]);
        $this->assertEquals([
            'item1' => 'itemValue1',
            'item2' => 'itemValue2'
        ], $model->get('map1'));
        $this->assertEquals('test1', $model->get('value1'));

        $model->replaceValues(['map1' => ['item2' => 'override']]);

        $this->assertEquals([
            'item1' => 'itemValue1',
            'item2' => 'override'
        ], $model->get('map1'));
    }

}
