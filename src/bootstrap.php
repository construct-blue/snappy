<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\SnApp\Blue\BlueSnapp;
use Blue\SnApp\Cms\CmsSnapp;
use Blue\SnApp\Kleinschuster\KleinschusterSnapp;
use Blue\SnApp\Nicemobil\NicemobilSnapp;
use Blue\SnApp\System\SystemSnapp;

global $env;

$cache = fn(string $key) =>  dirname(__DIR__) . "/data/$key.config.cache.php";

$app = SnappyServer::fromEnv($env, $cache('server'));
$app->app(BlueSnapp::fromEnv($env, $cache('blue')), '/');
$app->app(SystemSnapp::fromEnv($env, $cache('system')), '/system');
$app->app(CmsSnapp::fromEnv($env, $cache('cms')), '/cms');
$app->app(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at');
$app->app(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.nicemobil.blog');
$app->app(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.kleinschuster.de');
$app->app(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social');

return $app;
