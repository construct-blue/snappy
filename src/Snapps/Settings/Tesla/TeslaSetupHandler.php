<?php

namespace Blue\Snapps\Settings\Tesla;

use Blue\Core\Application\Handler\TemplateHandler;
use Blue\Logic\Client\Tesla\TeslaClientRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TeslaSetupHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $client = TeslaClientRepository::instance()->find();

        return new HtmlResponse($this->render(TeslaSetup::class, [
            'url' => (string)$client->getLoginUri(),
            'code' => $client->getCode(),
            'state' => $client->getState(),
            'current' => $client->getEmail(),
            'proxy' => $client->getProxy(),
        ]));
    }
}