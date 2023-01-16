<?php

declare(strict_types=1);

namespace Blue\Core\Analytics;

use JsonSerializable;

/**
 * @internal
 */
final class Entry implements JsonSerializable
{
    private string $id;
    private int $timestamp;
    private ?string $requestId = null;
    private ?int $timestampUnload = null;
    private string $userId = '';
    private string $sessionId = '';
    private string $sessionLanguage = '';
    private string $headerLocale = '';
    private string $headerLanguage = '';
    private string $headerRegion = '';
    private string $userAgent = '';
    private string $host = '';
    private string $path = '';
    private string $referrer = '';
    private string $utmSource = '';
    private string $utmMedium = '';
    private string $utmCampaign = '';
    private string $utmTerm = '';
    private string $utmContent = '';
    private int $statusCode = 0;
    private string $reasonPhrase = '';
    private string $clickHref = '';

    private ClientHints $clientHints;

    public function __construct()
    {
        $this->id = uniqid();
        $this->timestamp = time();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @param string|null $requestId
     * @return Entry
     */
    public function setRequestId(?string $requestId): Entry
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): Entry
    {
        $this->userId = $userId;
        return $this;
    }


    public function getTimestampUnload(): ?int
    {
        return $this->timestampUnload;
    }

    public function setTimestampUnload(?int $timestampUnload): Entry
    {
        $this->timestampUnload = $timestampUnload;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return Entry
     */
    public function setSessionId(string $sessionId): Entry
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionLanguage(): string
    {
        return $this->sessionLanguage;
    }

    /**
     * @param string $sessionLanguage
     * @return Entry
     */
    public function setSessionLanguage(string $sessionLanguage): Entry
    {
        $this->sessionLanguage = $sessionLanguage;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderLanguage(): string
    {
        return $this->headerLanguage;
    }

    /**
     * @param string $headerLanguage
     * @return Entry
     */
    public function setHeaderLanguage(string $headerLanguage): Entry
    {
        $this->headerLanguage = $headerLanguage;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderRegion(): string
    {
        return $this->headerRegion;
    }

    /**
     * @param string $headerRegion
     * @return Entry
     */
    public function setHeaderRegion(string $headerRegion): Entry
    {
        $this->headerRegion = $headerRegion;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderLocale(): string
    {
        return $this->headerLocale;
    }

    /**
     * @param string $headerLocale
     * @return Entry
     */
    public function setHeaderLocale(string $headerLocale): Entry
    {
        $this->headerLocale = $headerLocale;
        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): Entry
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): Entry
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Entry
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Entry
     */
    public function setHost(string $host): Entry
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferrer(): string
    {
        return $this->referrer;
    }

    /**
     * @param string $referrer
     * @return Entry
     */
    public function setReferrer(string $referrer): Entry
    {
        $this->referrer = $referrer;
        return $this;
    }

    /**
     * @return string
     */
    public function getUtmSource(): string
    {
        return $this->utmSource;
    }

    /**
     * @param string $utmSource
     * @return Entry
     */
    public function setUtmSource(string $utmSource): Entry
    {
        $this->utmSource = $utmSource;
        return $this;
    }

    /**
     * @return string
     */
    public function getUtmMedium(): string
    {
        return $this->utmMedium;
    }

    /**
     * @param string $utmMedium
     * @return Entry
     */
    public function setUtmMedium(string $utmMedium): Entry
    {
        $this->utmMedium = $utmMedium;
        return $this;
    }

    /**
     * @return string
     */
    public function getUtmCampaign(): string
    {
        return $this->utmCampaign;
    }

    /**
     * @param string $utmCampaign
     * @return Entry
     */
    public function setUtmCampaign(string $utmCampaign): Entry
    {
        $this->utmCampaign = $utmCampaign;
        return $this;
    }

    /**
     * @return string
     */
    public function getUtmTerm(): string
    {
        return $this->utmTerm;
    }

    /**
     * @param string $utmTerm
     * @return Entry
     */
    public function setUtmTerm(string $utmTerm): Entry
    {
        $this->utmTerm = $utmTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getUtmContent(): string
    {
        return $this->utmContent;
    }

    /**
     * @param string $utmContent
     * @return Entry
     */
    public function setUtmContent(string $utmContent): Entry
    {
        $this->utmContent = $utmContent;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Entry
     */
    public function setStatusCode(int $statusCode): Entry
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * @param string $reasonPhrase
     * @return Entry
     */
    public function setReasonPhrase(string $reasonPhrase): Entry
    {
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    /**
     * @return string
     */
    public function getClickHref(): string
    {
        return $this->clickHref;
    }

    /**
     * @param string $clickHref
     * @return Entry
     */
    public function setClickHref(string $clickHref): Entry
    {
        $this->clickHref = $clickHref;
        return $this;
    }

    /**
     * @return ClientHints
     */
    public function getClientHints(): ClientHints
    {
        return $this->clientHints;
    }

    /**
     * @param ClientHints $clientHints
     * @return Entry
     */
    public function setClientHints(ClientHints $clientHints): Entry
    {
        $this->clientHints = $clientHints;
        return $this;
    }


    public static function __set_state(array $data)
    {
        $entry = new Entry();

        $entry->requestId = $data['requestId'];
        $entry->userId = $data['userId'];
        $entry->sessionId = $data['sessionId'];
        $entry->sessionLanguage = $data['sessionLanguage'];
        $entry->timestamp = $data['timestamp'];
        $entry->userAgent = $data['userAgent'];
        $entry->path = $data['path'];
        $entry->host = $data['host'];
        $entry->referrer = $data['referrer'] ?? '';
        $entry->statusCode = $data['statusCode'];
        $entry->reasonPhrase = $data['reasonPhrase'];
        $entry->headerLanguage = $data['headerLanguage'];
        $entry->headerRegion = $data['headerRegion'];
        $entry->headerLocale = $data['headerLocale'];
        $entry->utmContent = $data['utmContent'];
        $entry->utmCampaign = $data['utmCampaign'];
        $entry->utmMedium = $data['utmMedium'];
        $entry->utmTerm = $data['utmTerm'];
        $entry->utmSource = $data['utmSource'];
        $entry->timestampUnload = $data['timestampUnload'];
        $entry->clickHref = $data['clickHref'] ?? '';

        if (isset($data['clientHints'])) {
            if (is_array($data['clientHints'])) {
                $entry->clientHints = ClientHints::__set_state($data['clientHints']);
            } else {
                $entry->clientHints = $data['clientHints'];
            }
        }

        return $entry;
    }

    public function jsonSerialize(): array
    {
        $result = [
            'requestId' => $this->requestId,
            'userId' => $this->userId,
            'sessionId' => $this->sessionId,
            'sessionLanguage' => $this->sessionLanguage,
            'timestamp' => $this->timestamp,
            'userAgent' => $this->userAgent,
            'path' => $this->path,
            'host' => $this->host,
            'referrer' => $this->referrer,
            'statusCode' => $this->statusCode,
            'reasonPhrase' => $this->reasonPhrase,
            'headerLanguage' => $this->headerLanguage,
            'headerRegion' => $this->headerRegion,
            'headerLocale' => $this->headerLocale,
            'utmContent' => $this->utmContent,
            'utmMedium' => $this->utmMedium,
            'utmTerm' => $this->utmTerm,
            'utmCampaign' => $this->utmCampaign,
            'utmSource' => $this->utmSource,
            'timestampUnload' => $this->timestampUnload,
            'clickHref' => $this->clickHref,
        ];

        if (isset($this->clientHints)) {
            $result['clientHints'] = $this->clientHints;
        }
        return $result;
    }
}
