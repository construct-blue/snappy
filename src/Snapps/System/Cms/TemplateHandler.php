<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms;

use Blue\Models\User\User;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class TemplateHandler extends \Blue\Snapps\System\TemplateHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uriBuilder = $this->getUriBuilder($request);
        /** @var User|null $user */
        $user = $request->getAttribute(User::class);
        $snappCode = $request->getAttribute('snapp');
        if ($snappCode && !$user?->hasSnapp($snappCode)) {
            return $handler->handle($request);
        }

        $siteSnapps = [];
        foreach ($this->getSnapps($request) as $snapp) {
            if ($snapp->isSite() && $user?->hasSnapp($snapp->getCode())) {
                $siteSnapps[] = $snapp;
            }
        }

        $this->assign('siteSnapps', $siteSnapps);

        $this->assign('basePath', (string)$uriBuilder->withMatchedRoutePath(['snapp' => null]));
        $this->assign('blocksPath', (string)$uriBuilder->withRoute('blocks', ['snapp' => $snappCode]));
        $this->assign('pagesPath', (string)$uriBuilder->withRoute('pages', ['snapp' => $snappCode]));

        if (!$snappCode) {
            $firstSnapp = reset($siteSnapps);
            if (!$firstSnapp) {
                return $handler->handle($request);
            }
            return new RedirectResponse(
                (string)$uriBuilder
                    ->withCurrentUri()
                    ->withAppendedPath("/{$firstSnapp->getCode()}")
            );
        }

        foreach ($this->getSnapps($request) as $snapp) {
            if ($snapp->getCode() === $snappCode) {
                $this->assign('snapp', $snapp);
            }
        }

        return parent::process($request, $handler);
    }
}
