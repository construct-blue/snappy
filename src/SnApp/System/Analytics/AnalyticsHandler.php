<?php

declare(strict_types=1);

namespace Blue\SnApp\System\Analytics;

use Laminas\Diactoros\Response\HtmlResponse;
use Blue\Core\Analytics\AnalyticsDayRepository;
use Blue\Core\Analytics\AnalyticsEntryRepository;
use Blue\Core\Application\Handler\TemplateHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AnalyticsHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $code = $request->getAttribute('code');

        if ($code &&  AnalyticsDayRepository::instance()->existsByCode($code)) {
            $this->assign('summary', AnalyticsDayRepository::instance()->findByCode($code));
        } else {
            $this->assign('summary', AnalyticsDayRepository::instance()->findToday());
        }

        $this->assign('codes', iterator_to_array(AnalyticsDayRepository::instance()->findAllCodes()));

        return new HtmlResponse($this->render(AnalyticsView::class));
    }
}
