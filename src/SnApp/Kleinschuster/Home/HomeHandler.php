<?php

namespace Blue\SnApp\Kleinschuster\Home;

use Blue\SnApp\Kleinschuster\Home\Home;
use Laminas\Diactoros\Response\HtmlResponse;
use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Logic\Block\BlockRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assign('blocks', iterator_to_array(BlockRepository::instance()->findAll()));
        return new HtmlResponse($this->render(Home::class));
    }
}
