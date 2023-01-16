<?php

declare(strict_types=1);

namespace Blue\Core\Authentication;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Uri;
use Mezzio\Template\TemplateRendererInterface;
use Blue\Core\Application\Ingress\IngressResult;
use Blue\Core\Application\Session\Session;
use Blue\Core\Database\Exception\DatabaseException;
use Blue\Core\Http\Method;
use Blue\Core\Http\Uri\UriBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $renderer;
    private array $config;
    private UserRepository $userRepository;

    public function __construct(TemplateRendererInterface $renderer, array $config, UserRepository $userRepository)
    {
        $this->renderer = $renderer;
        $this->config = $config;
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);
        /** @var IngressResult $ingressResult */
        $ingressResult = $request->getAttribute(IngressResult::class);
        $uriBuilder = $ingressResult->getUriBuilder();
        $loginPath = (string)$uriBuilder
            ->withPath($this->config['login_path'])
            ->withParams(['redirect' => (string)$uriBuilder->withCurrentUri()]);
        if (Method::POST->matches($request)) {
            $params = $request->getParsedBody();
            if (!empty($params['token']) && !empty($params['username']) && !empty($params['password'])) {
                if ($session->getToken() === $params['token']) {
                    $session->renewToken();
                    try {
                        $user = $this->userRepository->findByName($params['username']);
                    } catch (DatabaseException $exception) {
                        $user = null;
                    }
                    if (isset($user) && $user->isActive() && $user->verifyPassword($params['password'])) {
                        $session->setUser($user);
                        if (isset($request->getQueryParams()['redirect'])) {
                            $redirectPath = (new Uri($request->getQueryParams()['redirect']))->getPath();
                            return new RedirectResponse($redirectPath);
                        }
                    } else {
                        $session->addMessage('Invalid credentials');
                    }
                } else {
                    $session->addMessage('Invalid session');
                }
            }
        }

        $params = $request->getQueryParams();
        if (isset($params['logout']) && $params['logout'] === '1') {
            $session->setUser(null);
            return new RedirectResponse((string)$uriBuilder->withCurrentUri()->withRemovedParam('logout'));
        }

        if (null === $session->getUser() && !empty($this->config['default_user'])) {
            $session->setUser($this->userRepository->findByName($this->config['default_user']));
        }

        $this->prepareTemplateVariables($uriBuilder, $session, $loginPath);

        if (null === $session->getUser() || $request->getUri()->getPath() === $this->config['login_path']) {
            $params = ['token' => $session->getToken(), 'messages' => $session->getMessages()];
            $session->resetMessages();
            return new HtmlResponse(
                $this->renderer->render($this->config['template'], $params)
            );
        }

        return $handler->handle($request->withAttribute(User::class, $session->getUser()));
    }


    private function prepareTemplateVariables(
        UriBuilder $uriBuilder,
        Session $session,
        string $loginPath
    ): void {
        $this->renderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'loginPath',
            $loginPath
        );
        $this->renderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'logoutPath',
            (string)$uriBuilder->withCurrentUri()->withAppendedParams(['logout' => '1'])
        );
        if ($session->getUser()) {
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'user',
                $session->getUser()
            );
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'userIsGuest',
                $session->getUser()->isGuest()
            );
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'userIsAdmin',
                $session->getUser()->isAdmin()
            );
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'userName',
                $session->getUser()->getName()
            );
        }
    }
}
