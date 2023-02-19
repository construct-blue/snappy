<?php
declare(strict_types=1);

namespace Blue\Core\View;

class ViewModel implements ViewModelInterface
{
    private array $defaults = [];
    public function __construct(private array $data = [])
    {
    }

    public function replaceValues(array $params): void
    {
        if ([] === $params) {
            return;
        }
        if ([] === $this->data) {
            $this->data = $params;
        } else {
            $this->data = array_replace_recursive($this->data, $params);
        }
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? $this->defaults[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function setDefault(string $key, mixed $value): void
    {
        $this->defaults[$key] = $value;
    }
}