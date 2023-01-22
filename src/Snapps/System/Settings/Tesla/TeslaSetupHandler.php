<?php

namespace Blue\Snapps\System\Settings\Tesla;

use Blue\Models\TeslaClient\TeslaClientRepository;
use Blue\Snapps\System\TemplateHandler;
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
