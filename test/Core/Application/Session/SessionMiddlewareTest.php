<?php

declare(strict_types=1);

namespace BlueTest\Core\Application\Session;

use Blue\Core\Application\Session\Session;
use Blue\Core\Application\Session\SessionContainer;
use Blue\Core\Application\Session\SessionMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddlewareTest extends TestCase
{
    public function testShouldSetCookieWhenNotSetAndSessionModified()
    {
        $sessionContainer = new SessionContainer();
        $middleware = new SessionMiddleware($sessionContainer);
        $response = $middleware->process(
            new ServerRequest(),
            new class implements RequestHandlerInterface {
                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return new Response();
                }
            }
        );
        $this->assertEmpty($response->getHeader('Set-Cookie'));

        $response = $middleware->process(
            new ServerRequest(),
            new class implements RequestHandlerInterface {
                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    /** @var Session $session */
                    $session = $request->getAttribute(Session::class);
                    $session->setUserId('test');
                    return new Response();
                }
            }
        );
        $this->assertNotEmpty($response->getHeader('Set-Cookie'));
    }

    public function testShouldLoadSessionWhenCookieIsSet()
    {
        $session = new Session();
        $id = $session->getId();
        $session->setUserId('test');
        unset($session);

        $sessionContainer = new SessionContainer();
        $middleware = new SessionMiddleware($sessionContainer);
        $handler = new class implements RequestHandlerInterface {
            public Session $session;

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->session = $request->getAttribute(Session::class);
                return new Response();
            }
        };
        $request = (new ServerRequest())->withCookieParams([Session::COOKIE_NAME => $id]);
        $response = $middleware->process($request, $handler);
        $this->assertEmpty($response->getHeader('Set-Cookie'));
        $this->assertTrue($handler->session->isLoggedIn());
    }
}
