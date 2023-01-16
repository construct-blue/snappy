<?php

namespace BlueTest\Core\Application\Ingress;

use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Router\RouteResult;
use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Ingress\IngressRoute;
use PHPUnit\Framework\TestCase;

class IngressRouteTest extends TestCase
{
    public function testProcessSuccessWithPath()
    {
        $app = ApplicationStub::fromEnv([])->resolve();
        $app->get('/', fn() => new TextResponse('test'));

        $handler = new HandlerStub();
        $route = IngressRoute::app($app, '/test');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/test'));
        $route->process($request, $handler);
        $response = $handler->request->getAttribute(IngressResult::class)->process($request, $handler);
        $this->assertEquals('test', $response->getBody()->getContents());
        $this->assertInstanceOf(IngressResult::class, $handler->request->getAttribute(IngressResult::class));
        $this->assertInstanceOf(RouteResult::class, $handler->request->getAttribute(RouteResult::class));
    }

    public function testProcessSuccessWithDomain()
    {
        $app = ApplicationStub::fromEnv([])->resolve();
        $app->get('/', fn() => new TextResponse('test'));

        $handler = new HandlerStub();
        $route = IngressRoute::app($app, '/test', 'www.example.com');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/test'));
        $route->process($request, $handler);
        $response = $handler->request->getAttribute(IngressResult::class)->process($request, $handler);
        $this->assertEquals('test', $response->getBody()->getContents());
        $this->assertInstanceOf(IngressResult::class, $handler->request->getAttribute(IngressResult::class));
        $this->assertInstanceOf(RouteResult::class, $handler->request->getAttribute(RouteResult::class));
    }

    public function testProcessFailure()
    {
        $app = ApplicationStub::fromEnv([]);
        $handler = new HandlerStub();
        $route = IngressRoute::app($app, '/test');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/foo'));
        $route->process($request, $handler);
        $this->assertEquals('/foo', $handler->request->getUri()->getPath());
        $this->assertNull($handler->request->getAttribute(IngressResult::class));
        $this->assertNull($handler->request->getAttribute(RouteResult::class));
    }
}
