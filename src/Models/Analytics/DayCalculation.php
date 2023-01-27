<?php

declare(strict_types=1);

namespace Blue\Models\Analytics;

use Blue\Core\I18n\Region;
use Blue\Core\Queue\Queue;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;

class DayCalculation
{
    public function queueExecution(): void
    {
        Queue::instance()->deferTask($this->execute(...));
    }

    public function execute(bool $force = false): void
    {
        $day = new Day();
        if (AnalyticsDayRepository::instance()->existsByCode($day->getCode())) {
            $day = AnalyticsDayRepository::instance()->findByCode($day->getCode());
        }

        // only calculate if data is more than 1 minute old
        if (!$force && time() - $day->getModified() < 60) {
            return;
        }
        $changed = false;
        $modified = $day->getModified();
        $day->setModified(time());
        /** @var Entry $entry */
        foreach (AnalyticsEntryRepository::instance()->findSince($modified) as $entry) {
            if (!$entry->getTimestampUnload()) {
                continue;
            }
            $detector = new DeviceDetector($entry->getUserAgent(), $entry->getClientHints());
            $detector->parse();
            if ($detector->isBot()) {
                continue;
            }
            $changed = true;
            $day->setVisits($day->getVisits() + 1);
            $day->setDeviceVisits($this->count($detector->getDeviceName(), $day->getDeviceVisits()));
            if ($detector->isMobile()) {
                $day->setMobileVisits($day->getMobileVisits() + 1);
            }
            if ($detector->isDesktop()) {
                $day->setDesktopVisits($day->getDesktopVisits() + 1);
            }
            $url = $entry->getHost() . $entry->getPath();
            $day->setUrlVisits($this->count($url, $day->getUrlVisits()));
            $day->setLanguageVisits($this->count($entry->getHeaderLanguage(), $day->getLanguageVisits()));
            $browserFamily = Browser::getBrowserFamily($detector->getClient('short_name'));
            // safari reporting incorrect region
            if ($browserFamily === 'Safari') {
                $day->setRegionVisits($this->count(Region::WORLD->value, $day->getRegionVisits()));
            } else {
                $day->setRegionVisits($this->count($entry->getHeaderRegion(), $day->getRegionVisits()));
            }
            $duration = $entry->getTimestampUnload() - $entry->getTimestamp();
            $day->setUrlDuration($this->avg($url, $duration, $day->getUrlDuration()));
            if ($entry->getReferrer()) {
                $day->setReferrers($this->count($entry->getReferrer(), $day->getReferrers()));
            }
            if ($entry->getClickHref()) {
                $day->setActions($this->count($entry->getClickHref(), $day->getActions()));
            }
        }
        if ($changed) {
            AnalyticsDayRepository::instance()->save($day);
        }
    }

    private function avg(string $key, int $value, array $data): array
    {
        if (isset($data[$key])) {
            $data[$key] = ($data[$key] + $value) / count($data);
        } else {
            $data[$key] = $value;
        }
        return $data;
    }

    private function count(string $key, array $data): array
    {
        if (isset($data[$key])) {
            $data[$key]++;
        } else {
            $data[$key] = 1;
        }
        return $data;
    }
}
