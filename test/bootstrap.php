<?php

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

ini_set('open_basedir', realpath('.'));

\Blue\Core\Logger\Logger::$logger = new \Psr\Log\NullLogger();
\Blue\Core\Database\Connection::$test = true;
\Blue\Core\Environment\Environment::instance()->setData(include 'test-env.php');
\Blue\Core\Environment\Environment::instance()->setRoot(__DIR__);