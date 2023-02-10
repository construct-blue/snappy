<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

ini_set('zend.exception_ignore_args', true);

require "vendor/autoload.php";

/** @var Blue\Core\Application\Server\SnappyServer $application */
$application = require "src/server.php";
$application->run();
