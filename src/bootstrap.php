<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\Snapps\Blue\BlueSnapp;
use Blue\Snapps\Cms\CmsSnapp;
use Blue\Snapps\Kleinschuster\KleinschusterSnapp;
use Blue\Snapps\Nicemobil\NicemobilSnapp;
use Blue\Snapps\System\SystemSnapp;

global $env;

$cache = fn(string $key) => "data/$key";

$server = SnappyServer::fromEnv($env, $cache('server'));
$server->addSnapp(BlueSnapp::fromEnv($env, $cache('blue')), '/')
    ->setName('Snappy');
$server->addSnapp(SystemSnapp::fromEnv($env, $cache('system')), '/system')
    ->setName('System');
$server->addSnapp(CmsSnapp::fromEnv($env, $cache('cms')), '/cms')
    ->setName('CMS');

$server->addSnapp(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$server->addSnapp(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $server;
