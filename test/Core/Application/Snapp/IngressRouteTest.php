<?php

namespace BlueTest\Core\Application\Snapp;

use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Router\RouteResult;
use Blue\Core\Application\Snapp\SnappRouteResult;
use Blue\Core\Application\Snapp\SnappRoute;
use PHPUnit\Framework\TestCase;

class IngressRouteTest extends TestCase
{
    public function testProcessSuccessWithPath()
    {
        $app = ApplicationStub::default(['DEV_MODE' => true])->resolve();
        $app->get('/', fn() => new TextResponse('test'));

        $handler = new HandlerStub();
        $route = new SnappRoute($app, '/test');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/test'));
        $route->process($request, $handler);
        $response = $handler->request->getAttribute(SnappRouteResult::class)->process($request, $handler);
        $this->assertEquals('test', $response->getBody()->getContents());
        $this->assertInstanceOf(SnappRouteResult::class, $handler->request->getAttribute(SnappRouteResult::class));
        $this->assertInstanceOf(RouteResult::class, $handler->request->getAttribute(RouteResult::class));
    }

    public function testProcessSuccessWithDomain()
    {
        $app = ApplicationStub::default(['DEV_MODE' => true])->resolve();
        $app->get('/', fn() => new TextResponse('test'));

        $handler = new HandlerStub();
        $route = new SnappRoute($app, '/test', 'www.example.com');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/test'));
        $route->process($request, $handler);
        $response = $handler->request->getAttribute(SnappRouteResult::class)->process($request, $handler);
        $this->assertEquals('test', $response->getBody()->getContents());
        $this->assertInstanceOf(SnappRouteResult::class, $handler->request->getAttribute(SnappRouteResult::class));
        $this->assertInstanceOf(RouteResult::class, $handler->request->getAttribute(RouteResult::class));
    }

    public function testProcessFailure()
    {
        $app = ApplicationStub::default(['DEV_MODE' => true]);
        $handler = new HandlerStub();
        $route = new SnappRoute($app, '/test');
        $request = (new ServerRequest())->withUri(new Uri('https://www.example.com/foo'));
        $route->process($request, $handler);
        $this->assertEquals('/foo', $handler->request->getUri()->getPath());
        $this->assertNull($handler->request->getAttribute(SnappRouteResult::class));
        $this->assertNull($handler->request->getAttribute(RouteResult::class));
    }
}
