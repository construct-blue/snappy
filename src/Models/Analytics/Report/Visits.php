<?php

declare(strict_types=1);

namespace Blue\Models\Analytics\Report;

use Blue\Core\I18n\Region;
use Blue\Models\Analytics\Tracker\Entry;
use Blue\Models\Analytics\Tracker\EntryRepository;
use DateTime;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use Generator;

class Visits
{
    private Generator $entries;

    private int $total = 0;

    private array $byDate = [];
    private array $byRegion = [];
    private array $byLanguage = [];

    public function __construct(
        private readonly EntryRepository $entryRepository,
        DateTime $from,
        DateTime $to = new DateTime('now')
    ) {
        $this->entries = $this->entryRepository->findByDate($from, $to);
        $date = clone $from;

        do {
            $this->byDate[$date->format('Y-m-d')] = 0;
            $date->modify('+1 day');
        } while ($date <= $to);
    }

    public function calculate(): void
    {
        if ($this->entries->valid()) {
            /** @var Entry $entry */
            foreach ($this->entries as $entry) {
                $date = date('Y-m-d', $entry->getTimestamp());
                if (!isset($this->byDate[$date])) {
                    continue;
                }
                $detector = new DeviceDetector($entry->getUserAgent(), $entry->getClientHints());
                $detector->parse();
                if ($detector->isBot()) {
                    continue;
                }
                $this->total++;
                $this->byDate[$date]++;

                $browserFamily = Browser::getBrowserFamily($detector->getClient('short_name'));

                // safari reporting incorrect region
                if ($browserFamily === 'Safari') {
                    $region = Region::WORLD->value;
                } else {
                    $region = $entry->getHeaderRegion();
                }
                $this->byRegion[$region] = ($this->byRegion[$region] ?? 0) + 1;

                $language = $entry->getHeaderLanguage();
                $this->byLanguage[$language] = ($this->byLanguage[$language] ?? 0) + 1;
            }
        }
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getByDate(): array
    {
        return $this->byDate;
    }

    public function getByRegion(): array
    {
        return $this->byRegion;
    }

    public function getByLanguage(): array
    {
        return $this->byLanguage;
    }

    public function getByDateRelative(): array
    {
        $max = max($this->getByDate());
        if (!$max) {
            return $this->getByDate();
        }
        $result = [];
        foreach ($this->getByDate() as $date => $count) {
            $result[$date] = (int)round($count / $max * 100);
        }
        return $result;
    }


    public function getDateDistribution(): array
    {
        if (!$this->getTotal()) {
            return $this->getByDate();
        }
        $result = [];
        foreach ($this->getByDate() as $date => $count) {
            $result[$date] = (int)round($count / $this->getTotal() * 100);
        }
        return $result;
    }

    public function load(string $snappCode)
    {
    }
}
