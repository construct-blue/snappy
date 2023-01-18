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

$app = SnappyServer::fromEnv($env, $cache('server'));
$app->addSnapp(BlueSnapp::fromEnv($env, $cache('blue')), '/')
    ->setName('Home');
$app->addSnapp(SystemSnapp::fromEnv($env, $cache('system')), '/system')
    ->setName('System');
$app->addSnapp(CmsSnapp::fromEnv($env, $cache('cms')), '/cms')
    ->setName('CMS');

$app->addSnapp(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$app->addSnapp(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $app;
