<?php

declare(strict_types=1);

namespace Blue\Models\Analytics\Tracker;

use Blue\Core\Application\Session\Session;
use Blue\Core\Http\Header;
use Blue\Core\Http\QueryParameter;
use Blue\Core\Http\RequestAttribute;
use Blue\Core\Http\UrlSanitizer;
use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class EntryFactory
{
    public function create(ServerRequestInterface $request, ?ResponseInterface $response): Entry
    {
        $entry = new Entry();
        $entry->setClientHints(ClientHints::factory($request->getServerParams()));
        $entry->setRequestId(RequestAttribute::REQUEST_ID->getFrom($request));
        $entry->setTimestamp(RequestAttribute::REQUEST_TIMESTAMP->getFrom($request, $entry->getTimestamp()));

        /** @var Session|null $session */
        $session = $request->getAttribute(Session::class);
        if ($session) {
            $entry->setSessionId($session->getId());
            $entry->setSessionLanguage($session->getLanguage()->value);
            if ($session->getUser()?->isEditable()) {
                $entry->setUserId($session->getUser()->getId());
            }
        }
        $locale = Locale::acceptFromHttp(Header::ACCEPT_LANGUAGE->getFrom($request));
        if ($locale) {
            $entry->setHeaderLocale($locale);
            $language = Locale::getPrimaryLanguage($locale);
            if ($language) {
                $entry->setHeaderLanguage($language);
            }
            $region = Locale::getRegion($locale);
            if ($region) {
                $entry->setHeaderRegion($region);
            }
        }
        $entry->setUserAgent(Header::USER_AGENT->getFrom($request));
        $entry->setHost(Header::HOST->getFrom($request));
        $entry->setPath(UrlSanitizer::path($request->getRequestTarget()));
        $entry->setReferrer(UrlSanitizer::hostWithPath(Header::REFERER->getFrom($request)));

        $entry->setUtmSource(QueryParameter::utm_source->getFrom($request));
        $entry->setUtmCampaign(QueryParameter::utm_campaign->getFrom($request));
        $entry->setUtmMedium(QueryParameter::utm_medium->getFrom($request));
        $entry->setUtmTerm(QueryParameter::utm_term->getFrom($request));
        $entry->setUtmContent(QueryParameter::utm_content->getFrom($request));

        if (isset($response)) {
            $entry->setStatusCode($response->getStatusCode());
            $entry->setReasonPhrase($response->getReasonPhrase());
        }
        return $entry;
    }
}
