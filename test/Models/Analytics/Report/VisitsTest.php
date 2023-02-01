<?php

declare(strict_types=1);

namespace BlueTest\Models\Analytics\Report;

use Blue\Models\Analytics\Report\Visits;
use Blue\Models\Analytics\Tracker\EntryRepository;
use DateTime;
use PHPUnit\Framework\TestCase;

class VisitsTest extends TestCase
{
    public function testShouldInitFromDate()
    {
        $entryRepo = $this->getMockBuilder(EntryRepository::class)
            ->disableOriginalConstructor()->onlyMethods(['findByDate'])->getMock();
        $entryRepo->method('findByDate')->willReturnCallback(function () {
            foreach (glob(__DIR__ . '/../entries/*.php') as $file) {
                yield include $file;
            }
        });
        $visits = new Visits($entryRepo, new DateTime('2023-01-23'), new DateTime('2023-01-27'));
        $visits->calculate();
        $this->assertEquals(52, $visits->getTotal());
        $this->assertEquals([
            '2023-01-27' => 41,
            '2023-01-23' => 9,
            '2023-01-24' => 2,
            '2023-01-25' => 0,
            '2023-01-26' => 0,
        ], $visits->getByDate());
        $this->assertEquals([
            '2023-01-27' => 100,
            '2023-01-23' => 22,
            '2023-01-24' => 5,
            '2023-01-25' => 0,
            '2023-01-26' => 0,
        ], $visits->getByDateRelative());
        $this->assertEquals([
            '2023-01-27' => 79,
            '2023-01-23' => 17,
            '2023-01-24' => 4,
            '2023-01-25' => 0,
            '2023-01-26' => 0,
        ], $visits->getDateDistribution());
        $this->assertEquals([
            'AT' => 31,
            'DE' => 1,
            '001' => 20,
        ], $visits->getByRegion());
        $this->assertEquals([
            'de' => 52,
        ], $visits->getByLanguage());
    }
}
