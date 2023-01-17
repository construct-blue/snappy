<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\SnApp\Blue\BlueSnapp;
use Blue\SnApp\Cms\CmsSnapp;
use Blue\SnApp\Kleinschuster\KleinschusterSnapp;
use Blue\SnApp\Nicemobil\NicemobilSnapp;
use Blue\SnApp\System\SystemSnapp;

global $env;

$cache = fn(string $key) => dirname(__DIR__) . "/data/$key.config.cache.php";

$app = SnappyServer::fromEnv($env, $cache('server'));
$app->addSnApp(BlueSnapp::fromEnv($env, $cache('blue')), '/');
$app->addSnApp(SystemSnapp::fromEnv($env, $cache('system')), '/system');
$app->addSnApp(CmsSnapp::fromEnv($env, $cache('cms')), '/cms');

$app->addSnApp(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$app->addSnApp(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $app;
