<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Laminas\ServiceManager\ServiceManager;

class ApplicationContainer extends ServiceManager
{
    public function __construct(ApplicationContainerConfig $config)
    {
        parent::__construct($config->toArray());
    }
}
