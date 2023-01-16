<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function is_array;

class AuthorizationMiddleware implements MiddlewareInterface
{
    public const ROUTE_OPTION_ROLES = 'roles';

    private TemplateRendererInterface $renderer;
    private array $config;

    public function __construct(TemplateRendererInterface $renderer, array $config)
    {
        $this->renderer = $renderer;
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var User|null $user */
        $user = $request->getAttribute(User::class);
        /** @var RouteResult|null $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if ($routeResult->isSuccess()) {
            $routeOptions = $routeResult->getMatchedRoute()->getOptions();
            if (
                $user && isset($routeOptions[self::ROUTE_OPTION_ROLES]) && is_array(
                    $routeOptions[self::ROUTE_OPTION_ROLES]
                )
            ) {
                if (
                    count(
                        array_uintersect(
                            $routeOptions[self::ROUTE_OPTION_ROLES],
                            $user->getRoles(),
                            UserRole::compare(...)
                        )
                    )
                ) {
                    return $handler->handle($request);
                }
            }
            if (empty($routeOptions[self::ROUTE_OPTION_ROLES])) {
                return $handler->handle($request);
            }
        } else {
            return $handler->handle($request);
        }
        if (isset($this->config['template'])) {
            return new HtmlResponse($this->renderer->render($this->config['template'], ['request' => $request]), 403);
        } else {
            return $handler->handle($request->withoutAttribute(RouteResult::class));
        }
    }
}
