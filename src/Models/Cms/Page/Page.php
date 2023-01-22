<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Page;

use JsonSerializable;

class Page implements JsonSerializable
{
    private string $id;
    private ?string $code = null;
    private string $title = 'New Page';
    private string $description = '';
    private string $main = '';
    private string $header = '{block:header}';
    private string $footer = '{block:footer}';

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
        $this->code = '/' . ltrim($code, '/');
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

    /**
     * @return string
     */
    public function getMain(): string
    {
        return $this->main;
    }

    /**
     * @param string $main
     * @return Page
     */
    public function setMain(string $main): Page
    {
        $this->main = $main;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return Page
     */
    public function setHeader(string $header): Page
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return $this->footer;
    }

    /**
     * @param string $footer
     * @return Page
     */
    public function setFooter(string $footer): Page
    {
        $this->footer = $footer;
        return $this;
    }

    public static function __set_state(array $data): object
    {
        $page = new Page();
        $page->id = $data['id'];
        $page->code = $data['code'] ?? null;
        $page->title = $data['title'] ?? '';
        $page->description = $data['description'] ?? '';
        $page->main = $data['main'] ?? '';
        $page->footer = $data['footer'] ?? '';
        $page->header = $data['header'] ?? '';
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
