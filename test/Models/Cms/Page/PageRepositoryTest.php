<?php
declare(strict_types=1);

namespace BlueTest\Models\Cms\Page;

use Blue\Models\Cms\Page\Page;
use Blue\Models\Cms\Page\PageRepository;
use PHPUnit\Framework\TestCase;

class PageRepositoryTest extends TestCase
{
    public function testSavePage()
    {
        $page = new Page();
        $page->setCode('code');
        $repo = new PageRepository('test');
        $repo->save($page);
        $this->assertEquals($page, $repo->findByCode($page->getCode()));
    }
}
