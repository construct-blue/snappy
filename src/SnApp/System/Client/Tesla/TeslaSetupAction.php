<?php

declare(strict_types=1);

namespace Blue\SnApp\System\Client\Tesla;

use Laminas\Diactoros\Response;
use Blue\Logic\Client\Tesla\TeslaClient;
use Blue\Logic\Client\Tesla\TeslaClientRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TeslaSetupAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $client = TeslaClientRepository::instance()->find();

        if (isset($data['proxy'])) {
            if ($data['proxy'] == '') {
                $client->setProxy(null);
            } else {
                $client->setProxy($data['proxy']);
            }
        }

        if (isset($data['url'])) {
            $client->fetchAccessToken($data['url']);
        }

        TeslaClientRepository::instance()->save($client);

        return new Response();
    }
}
