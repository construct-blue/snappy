<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Page;

use Blue\Snapps\System\Cms\ActionHandler;
use Blue\Core\Application\Session\MessageType;
use Blue\Models\Cms\Page\PageRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageDeleteAction extends ActionHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snapp = $request->getAttribute('snapp');
        $data = $request->getParsedBody();
        if (isset($data['id'])) {
            $repo = new PageRepository($snapp);
            $repo->delete($data['id']);
            $this->getSession($request)->addMessage('Page deleted successfully', MessageType::SUCCESS);
        }
        return new Response();
    }
}
