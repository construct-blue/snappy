<?php

declare(strict_types=1);

namespace Blue\Cms\Block;

use JsonSerializable;

final class Block implements JsonSerializable
{
    private string $id;
    private ?string $code = null;
    private string $content = '';

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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Block
     */
    public function setContent(string $content): Block
    {
        $this->content = $content;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'content' => $this->content
        ];
    }

    public static function __set_state(array $data)
    {
        $block = new Block();
        $block->id = $data['id'];
        $block->code = $data['code'] ?? null;
        $block->content = $data['content'] ?? '';
        return $block;
    }
}
