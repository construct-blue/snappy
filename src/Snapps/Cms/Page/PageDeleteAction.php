<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page;

use Blue\Cms\Page\PageRepository;
use Blue\Core\Application\Handler\ActionHandler;
use Blue\Core\View\Component\Toast\ToastType;
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
            $this->getSession($request)->addMessage('Page deleted successfully', ToastType::SUCCESS);
        }
        return new Response();
    }
}
