<?php

declare(strict_types=1);

namespace Blue\Cms\Page;

use JsonSerializable;

class Page implements JsonSerializable
{
    private string $id;
    private ?string $code = null;
    private string $title = '';
    private string $description = '';
    private array $content = [];

    public function __construct()
    {
        $this->id = uniqid();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): Page
    {
        $this->code = $code;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Page
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Page
    {
        $this->description = $description;
        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): Page
    {
        $this->content = $content;
        return $this;
    }

    public static function __set_state(array $data): object
    {
        $page = new Page();
        $page->id = $data['id'];
        $page->content = $data['content'];
        return $page;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'conent' => $this->content
        ];
    }
}
