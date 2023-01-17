<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Core\Application\Session\Session;
use Blue\Logic\Block\BlockRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        $this->assign('messages', $session->getMessages());
        $session->resetMessages();
        $blocks = iterator_to_array(BlockRepository::instance()->findAll());
        return new HtmlResponse(
            $this->render(BlockView::class, ['blocks' => $blocks])
        );
    }
}
