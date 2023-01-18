<?php

declare(strict_types=1);

namespace Blue\Cms\Block;

use JsonSerializable;

final class Block implements JsonSerializable
{
    private string $id;
    private ?string $code = null;
    private array $content = [];

    public function __construct()
    {
        $this->id = uniqid();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Block
     */
    public function setCode(?string $code): Block
    {
        $this->code = $code;
        return $this;
    }


    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return Block
     */
    public function setContent(array $content): Block
    {
        $this->content = $content;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'content' => $this->getContent()
        ];
    }

    public static function __set_state(array $data)
    {
        $block = new Block();
        $block->id = $data['id'];
        $block->code = $data['code'] ?? null;
        $block->content = $data['content'];
        return $block;
    }
}
