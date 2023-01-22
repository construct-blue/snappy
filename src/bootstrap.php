<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\Snapps\Kleinschuster\KleinschusterSnapp;
use Blue\Snapps\Nicemobil\NicemobilSnapp;
use Blue\Snapps\System\SystemSnapp;

global $env;

$cache = fn(string $key) => "data/$key";

$server = SnappyServer::fromEnv($env, $cache('server'));
$server->addSnapp(SystemSnapp::fromEnv($env, $cache('blue')), '/')
    ->setSite(false);


$server->addSnapp(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$server->addSnapp(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $server;
