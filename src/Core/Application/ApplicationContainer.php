<?php

declare(strict_types=1);

namespace Blue\Core\Application;

use Laminas\ServiceManager\ServiceManager;

class ApplicationContainer extends ServiceManager
{
    /**
     * @param ApplicationContainerConfig $config
     */
    public function __construct(ApplicationContainerConfig $config)
    {
        parent::__construct($config->toArray());
    }
}
