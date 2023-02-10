<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\Snapps\Kleinschuster\KleinschusterSnapp;
use Blue\Snapps\Nicemobil\NicemobilSnapp;
use Blue\Snapps\System\SystemSnapp;

$server = SnappyServer::default();
$server->addSnapp(SystemSnapp::default(), '/')
    ->setSite(false);

$server->addSnapp(NicemobilSnapp::default(), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$server->addSnapp(KleinschusterSnapp::default(), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $server;
