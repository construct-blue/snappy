<?php

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

ini_set('xdebug.max_nesting_level', 512);
\Blue\Core\Logger\Logger::$logger = new \Psr\Log\NullLogger();
\Blue\Core\Database\Connection::$test = true;
