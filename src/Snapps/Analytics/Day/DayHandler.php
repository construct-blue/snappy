<?php

declare(strict_types=1);

namespace Blue\Snapps\Analytics\Day;

use Blue\Core\Analytics\AnalyticsDayRepository;
use Blue\Core\Application\Handler\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DayHandler extends TemplateHandler
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

        return new HtmlResponse($this->render(DayView::class));
    }
}
