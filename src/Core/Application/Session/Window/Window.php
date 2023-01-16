<?php

declare(strict_types=1);

namespace Blue\Core\Application\Session\Window;

use Blue\Core\Application\Session\Session;
use Psr\Http\Server\MiddlewareInterface;

class Window
{
    public const COOKIE_NAME = 'wid';

    private string $id;
    private Session $session;
    private MiddlewareInterface $application;
    private ?int $socketFd = null;
    private ?Window $parent;
    private array $children = [];


    public function __construct(
        Session $session,
        MiddlewareInterface $application,
        ?Window $parent = null
    ) {
        $this->session = $session;
        $this->application = $application;
        $this->parent = $parent;
        $this->id = uniqid('w-');
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return MiddlewareInterface
     */
    public function getApplication(): MiddlewareInterface
    {
        return $this->application;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }


    /**
     * @return int|null
     */
    public function getSocketFd(): ?int
    {
        return $this->socketFd;
    }

    /**
     * @param int|null $socketFd
     * @return Window
     */
    public function setSocketFd(?int $socketFd): Window
    {
        $this->socketFd = $socketFd;
        return $this;
    }

    /**
     * @return Window|null
     */
    public function getParent(): ?Window
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function close(): void
    {
        $this->session->closeWindow($this->getId());
    }
}
