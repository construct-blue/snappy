<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\User;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Models\User\UserRepository;
use Blue\Snapps\System\Settings\User\View\UserView;
use Blue\Snapps\System\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $siteSnapps = array_filter($this->getSnapps($request), fn(SnappRoute $route) => $route->isSite());

        $snappOptions = [];
        foreach ($siteSnapps as $siteSnapp) {
            $snappOptions[$siteSnapp->getCode()] = $siteSnapp->getName();
        }
        $this->assign('snappOptions', $snappOptions);

        $this->assign('users', iterator_to_array(UserRepository::instance()->findAll()));
        return new HtmlResponse($this->render(UserView::class));
    }
}
