<?php

declare(strict_types=1);

use Blue\Core\Application\Server\SnappyServer;
use Blue\Snapps\Analytics\AnalyticsSnapp;
use Blue\Snapps\Blue\BlueSnapp;
use Blue\Snapps\Cms\CmsSnapp;
use Blue\Snapps\Kleinschuster\KleinschusterSnapp;
use Blue\Snapps\Nicemobil\NicemobilSnapp;
use Blue\Snapps\Settings\SettingsSnapp;

global $env;

$cache = fn(string $key) => "data/$key";

$server = SnappyServer::fromEnv($env, $cache('server'));
$server->addSnapp(BlueSnapp::fromEnv($env, $cache('blue')), '/')
    ->setSite(false)
    ->setName('Snappy');
$server->addSnapp(CmsSnapp::fromEnv($env, $cache('cms')), '/cms')
    ->setSite(false)
    ->setName('Content Manager');
$server->addSnapp(AnalyticsSnapp::fromEnv($env, $cache('analytics')), '/analytics')
    ->setSite(false)
    ->setName('Analytics');
$server->addSnapp(SettingsSnapp::fromEnv($env, $cache('settings')), '/settings')
    ->setSite(false)
    ->setName('Settings');


$server->addSnapp(NicemobilSnapp::fromEnv($env, $cache('nicemobil')), '/', 'live.sonice.at')
    ->setName('NICEmobil Live')
    ->addAlias('live.nicemobil.blog');

$server->addSnapp(KleinschusterSnapp::fromEnv($env, $cache('kleinschuster')), '/', 'www.robs.social')
    ->setName('Kleinschuster.de')
    ->addAlias('www.kleinschuster.de');

return $server;
