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
    private array $main = [];
    private array $header = [];
    private array $footer = [];

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

    public function getHeader(): array
    {
        return $this->header;
    }

    public function setHeader(array $header): Page
    {
        $this->header = $header;
        return $this;
    }

    public function getFooter(): array
    {
        return $this->footer;
    }

    public function setFooter(array $footer): Page
    {
        $this->footer = $footer;
        return $this;
    }

    public function getMain(): array
    {
        return $this->main;
    }

    public function setMain(array $main): Page
    {
        $this->main = $main;
        return $this;
    }

    public static function __set_state(array $data): object
    {
        $page = new Page();
        $page->id = $data['id'];
        $page->code = $data['code'] ?? null;
        $page->title = $data['title'] ?? '';
        $page->description = $data['description'] ?? '';
        $page->main = $data['main'] ?? [];
        $page->footer = $data['footer'] ?? [];
        $page->header = $data['header'] ?? [];
        return $page;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'main' => $this->main,
            'header' => $this->header,
            'footer' => $this->footer,
        ];
    }
}
