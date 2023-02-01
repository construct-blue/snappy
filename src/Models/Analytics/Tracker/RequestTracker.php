<?php

declare(strict_types=1);

namespace Blue\Models\Analytics\Tracker;

use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Core\Http\Method;
use Blue\Core\Http\QueryParameter;
use Blue\Core\Http\RequestAttribute;
use Blue\Core\Http\UrlSanitizer;
use Blue\Core\Queue\Queue;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class RequestTracker
{
    private ServerRequestInterface $request;

    private ResponseInterface $response;

    /**
     * @param ServerRequestInterface $request
     * @return RequestTracker
     */
    public function setRequest(ServerRequestInterface $request): RequestTracker
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return RequestTracker
     */
    public function setResponse(ResponseInterface $response): RequestTracker
    {
        $this->response = $response;
        return $this;
    }

    public function queueSave(): void
    {
        Queue::instance()->deferTask($this->save(...));
    }

    public function save(): void
    {
        if (isset($this->request) && Method::GET->matches($this->request)) {
            /** @var SnappRouteResult $routeResult */
            $routeResult = RequestAttribute::SNAPP_ROUTE_RESULT->getFrom($this->request);
            $snappCode = $routeResult->getRoute()->getCode();
            $entryRepository = new EntryRepository($snappCode);
            $requestId = QueryParameter::analytics_rid->getFrom($this->request);
            if ($requestId) {
                $entry = $entryRepository->findByRequestId($requestId);
            }
            if (!isset($entry)) {
                $entry = (new EntryFactory())->create($this->request, $this->response ?? null);
            }

            $requestTimestamp = QueryParameter::analytics_rt->getFrom($this->request);
            if ($requestTimestamp) {
                $entry->setTimestamp((int)$requestTimestamp);
            }

            $event = QueryParameter::analytics_event->getFrom($this->request);
            if ($event && Event::from($event)->is(Event::PAGE_HIDE)) {
                $entry->setTimestampUnload(time());
            }

            $click = QueryParameter::analytics_click->getFrom($this->request);
            if ($click) {
                $entry->setClickHref(UrlSanitizer::hostWithPath($click));
            }

            $entryRepository->save($entry);
        }
    }
}
