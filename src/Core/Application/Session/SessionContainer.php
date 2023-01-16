<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session;

class SessionContainer
{
    /**
     * @var Session[]
     */
    private array $clients = [];

    public function get(?string $id): Session
    {
        if (empty($id) || !isset($this->clients[$id])) {
            $session = new Session($id);
            $id = $session->getId();
            $this->clients[$id] = $session;
        }
        return $this->clients[$id];
    }
}
