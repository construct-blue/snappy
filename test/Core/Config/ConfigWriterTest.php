<?php

declare(strict_types=1);

namespace BlueTest\Core\Config;

use Blue\Core\Config\Config;
use Blue\Core\Config\ConfigWriter;
use PHPUnit\Framework\TestCase;

class ConfigWriterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $configWriter = new ConfigWriter(new Config(__DIR__ . '/examples/writer', ['test']));
        $configWriter->set('merge.existing', 'existing-value');
        $configWriter->write();
    }


    public function testWrite()
    {
        $configWriter = new ConfigWriter(new Config(__DIR__ . '/examples/writer', ['test']));
        $configWriter->set('string', 'default');
        $configWriter->set('array', ['first' => 'first-value', 'second' => 'second-value']);
        $configWriter->set('merge.new', 'new-value');
        $configWriter->write();

        $config = new Config(__DIR__ . '/examples/writer', ['test']);
        $this->assertEquals('default', $config->get('string'));
        $this->assertEquals(['first' => 'first-value', 'second' => 'second-value'], $config->get('array'));
        $this->assertEquals('new-value', $config->get('merge.new'));
        $this->assertEquals('existing-value', $config->get('merge.existing'));
    }
}
