<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Settings\Tesla;

use Blue\Models\TeslaClient\TeslaClientRepository;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TeslaSetupAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $client = TeslaClientRepository::instance()->find();

        if (isset($data['url'])) {
            $client->fetchAccessToken($data['url']);
        }

        TeslaClientRepository::instance()->save($client);

        return new Response();
    }
}
