<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

ini_set('zend.exception_ignore_args', true);

if (!@require "env.php") {
    clearCaches();
    require 'src/setup.php';
    exit;
}

clearCaches();
prepareEnvGlobal();

$autoloader = require "vendor/autoload.php";

/** @var Blue\Core\Application\AbstractSnapp $application */
$application = require "src/bootstrap.php";
$application->run();


function clearCaches(): void
{
    if (defined('DEV_MODE') && DEV_MODE) {
        clearstatcache();
        function_exists('opcache_reset') && opcache_reset();
        function_exists('apcu_clear_cache') && apcu_clear_cache();
        function_exists('apc_clear_cache') && apc_clear_cache();
    }
}

function prepareEnvGlobal(): void
{
    global $env;

    $env['MYSQL_HOST'] = MYSQL_HOST;
    $env['MYSQL_PORT'] = MYSQL_PORT;
    $env['MYSQL_DATABASE'] = MYSQL_DATABASE;
    $env['MYSQL_PASSWORD'] = MYSQL_PASSWORD;
    $env['MYSQL_USER'] = MYSQL_USER;

    if (defined('MYSQL_TABLE_PREFIX')) {
        $env['MYSQL_TABLE_PREFIX'] = MYSQL_TABLE_PREFIX;
    }

    if (defined('DEV_DOMAIN')) {
        $env['DEV_DOMAIN'] = DEV_DOMAIN;
    }
    if (defined('DEV_MODE')) {
        $env['DEV_MODE'] = DEV_MODE;
    }
}
