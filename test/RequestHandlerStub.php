<?php

namespace BlueTest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerStub implements RequestHandlerInterface
{
    private ServerRequestInterface $request;
    private ResponseInterface $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return $this->response;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return RequestHandlerStub
     */
    public function setRequest(ServerRequestInterface $request): RequestHandlerStub
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return RequestHandlerStub
     */
    public function setResponse(ResponseInterface $response): RequestHandlerStub
    {
        $this->response = $response;
        return $this;
    }
}