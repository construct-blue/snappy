<?php

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

\Blue\Core\Logger\Logger::$logger = new \Psr\Log\NullLogger();
\Blue\Core\Database\Connection::$test = true;
