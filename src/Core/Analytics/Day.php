<?php

declare(strict_types=1);

namespace Blue\Core\Analytics;

use DateTime;
use JsonSerializable;

final class Day implements JsonSerializable
{
    private string $id;
    private string $code;
    private int $modified;
    private int $visits = 0;
    private array $deviceVisits = [];
    private int $mobileVisits = 0;
    private int $desktopVisits = 0;
    private array $regionVisits = [];
    private array $languageVisits = [];
    private array $urlVisits = [];
    private array $urlDuration = [];
    private array $actions = [];
    private array $referrers = [];

    public function __construct()
    {
        $this->id = uniqid();
        $this->code = date('d-m-y');
        $this->modified = (new DateTime('today'))->getTimestamp();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getModified(): int
    {
        return $this->modified;
    }

    public function setModified(int $modified): Day
    {
        $this->modified = $modified;
        return $this;
    }

    public function getVisits(): int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): Day
    {
        $this->visits = $visits;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Day
     */
    public function setCode(string $code): Day
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array
     */
    public function getRegionVisits(): array
    {
        return $this->regionVisits;
    }

    /**
     * @param array $regionVisits
     * @return Day
     */
    public function setRegionVisits(array $regionVisits): Day
    {
        $this->regionVisits = $regionVisits;
        return $this;
    }

    /**
     * @return array
     */
    public function getLanguageVisits(): array
    {
        return $this->languageVisits;
    }

    /**
     * @param array $languageVisits
     * @return Day
     */
    public function setLanguageVisits(array $languageVisits): Day
    {
        $this->languageVisits = $languageVisits;
        return $this;
    }

    /**
     * @return array
     */
    public function getUrlVisits(): array
    {
        return $this->urlVisits;
    }

    /**
     * @param array $urlVisits
     * @return Day
     */
    public function setUrlVisits(array $urlVisits): Day
    {
        $this->urlVisits = $urlVisits;
        return $this;
    }

    /**
     * @return array
     */
    public function getReferrers(): array
    {
        return $this->referrers;
    }

    /**
     * @param array $referrers
     * @return Day
     */
    public function setReferrers(array $referrers): Day
    {
        $this->referrers = $referrers;
        return $this;
    }

    /**
     * @return array
     */
    public function getUrlDuration(): array
    {
        return $this->urlDuration;
    }

    /**
     * @param array $urlDuration
     * @return Day
     */
    public function setUrlDuration(array $urlDuration): Day
    {
        $this->urlDuration = $urlDuration;
        return $this;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return Day
     */
    public function setActions(array $actions): Day
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return array
     */
    public function getDeviceVisits(): array
    {
        return $this->deviceVisits;
    }

    /**
     * @param array $deviceVisits
     * @return Day
     */
    public function setDeviceVisits(array $deviceVisits): Day
    {
        $this->deviceVisits = $deviceVisits;
        return $this;
    }

    /**
     * @return int
     */
    public function getMobileVisits(): int
    {
        return $this->mobileVisits;
    }

    /**
     * @param int $mobileVisits
     * @return Day
     */
    public function setMobileVisits(int $mobileVisits): Day
    {
        $this->mobileVisits = $mobileVisits;
        return $this;
    }

    /**
     * @return int
     */
    public function getDesktopVisits(): int
    {
        return $this->desktopVisits;
    }

    /**
     * @param int $desktopVisits
     * @return Day
     */
    public function setDesktopVisits(int $desktopVisits): Day
    {
        $this->desktopVisits = $desktopVisits;
        return $this;
    }


    public static function __set_state(array $data)
    {
        $day = new Day();
        $day->id = $data['id'];
        $day->code = $data['code'];
        $day->modified = $data['modified'];
        $day->visits = $data['visits'];
        $day->regionVisits = $data['regionVisits'];
        $day->languageVisits = $data['languageVisits'];
        $day->urlVisits = $data['urlVisits'];
        $day->urlDuration = $data['urlDuration'] ?? [];
        $day->referrers = $data['referrers'];
        $day->actions = $data['actions'];
        $day->deviceVisits = $data['deviceVisits'];
        $day->mobileVisits = $data['mobileVisits'];
        $day->desktopVisits = $data['desktopVisits'];


        return $day;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'modified' => $this->modified,
            'visits' => $this->visits,
            'regionVisits' => $this->regionVisits,
            'languageVisits' => $this->languageVisits,
            'urlVisits' => $this->urlVisits,
            'urlDuration' => $this->urlDuration,
            'referrers' => $this->referrers,
            'actions' => $this->actions,
            'deviceVisits' => $this->deviceVisits,
            'mobileVisits' => $this->mobileVisits,
            'desktopVisits' => $this->desktopVisits,
        ];
    }
}
