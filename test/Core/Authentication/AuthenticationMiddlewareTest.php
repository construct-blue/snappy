<?php

namespace BlueTest\Core\Authentication;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Session\Session;
use Blue\Core\Authentication\AuthenticationMiddleware;
use Blue\Core\Authentication\ConfigProvider;
use BlueTest\RequestHandlerStub;
use PHPUnit\Framework\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    public function testProcess()
    {
        $renderer = $this->getMockBuilder(TemplateRendererInterface::class)->getMock();
        $config = (new ConfigProvider())()['authentication'];
        $session = new Session();

        $authMiddleware = new AuthenticationMiddleware($renderer, $config, UserRepositoryStub::instance());
        $postParams = [
            'username' => 'admin',
            'password' => 'admin',
            'token' => $session->getToken(),
        ];
        $request = new ServerRequest(
            [],
            [],
            new Uri($config['login_path']),
            'POST',
            'php://input',
            [],
            [],
            ['redirect' => 'path'],
            $postParams
        );
        $handler = new RequestHandlerStub(new Response());

        $application = ApplicationStub::fromEnv([]);
        $actualResponse = $authMiddleware->process(
            $request
                ->withAttribute(Session::class, $session)
                ->withAttribute(IngressResult::class, new IngressResult($application, '/')),
            $handler
        );
        $this->assertInstanceOf(Response\RedirectResponse::class, $actualResponse);
    }
}
