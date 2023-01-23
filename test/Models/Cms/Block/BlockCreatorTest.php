<?php

declare(strict_types=1);

namespace BlueTest\Models\Cms\Block;

use Blue\Models\Cms\Block\BlockCreator;
use Blue\Models\Cms\Block\BlockRepository;
use PHPUnit\Framework\TestCase;

class BlockCreatorTest extends TestCase
{
    public function testCreateMissing()
    {
        $repo = new BlockRepository('test');
        $creator = new BlockCreator($repo);
        $creator->createMissing('{block:foo}asdf{block:bar}');
        $this->assertTrue($repo->existsByCode('foo'));
        $this->assertTrue($repo->existsByCode('bar'));
        $this->assertFalse($repo->existsByCode('asdf'));
    }
}
