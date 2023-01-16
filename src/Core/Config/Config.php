<?php

declare(strict_types=1);

namespace Blue\Core\Config;

use function is_array;
use function in_array;
use function array_replace_recursive;
use function array_shift;
use function end;
use function count;
use function explode;
use function glob;
use function basename;

class Config
{
    private static string $defaultDirectory = __DIR__ . '/../../../data/config';
    private static ?string $defaultSuffix = null;

    protected array $data = [];
    protected array $suffixList;
    protected string $directory;

    final public function __construct(
        string $directory,
        array $suffixList
    ) {
        $this->suffixList = $suffixList;
        $this->directory = $directory;
        $this->load();
    }

    public function getSuffixList(): array
    {
        return $this->suffixList;
    }


    public function getDirectory(): string
    {
        return $this->directory;
    }

    public static function setDefaultDirectory(string $defaultDirectory): void
    {
        self::$defaultDirectory = $defaultDirectory;
    }

    public static function setDefaultSuffix(?string $defaultSuffix): void
    {
        self::$defaultSuffix = $defaultSuffix;
    }

    public static function default(): Config
    {
        return new Config(Config::$defaultDirectory, [Config::$defaultSuffix]);
    }

    protected function load()
    {
        foreach ($this->suffixList as $suffix) {
            $this->loadWithSuffix($suffix);
        }
    }

    private function loadWithSuffix(?string $suffix): void
    {
        $files = glob($this->buildPattern($suffix));
        foreach ($files as $file) {
            $key = basename($file, $this->buildSuffix($suffix));
            $exp = explode('.', $key);
            $foundSuffix = end($exp);
            if (!in_array($foundSuffix, $this->suffixList)) {
                $data = $this->loadFile($file);
                if (is_array($data)) {
                    $merged = array_replace_recursive(
                        $this->data[$key] ?? [],
                        $data
                    );
                    $this->data[$key] = $merged;
                } else {
                    $this->data[$key] = $data;
                }
            }
        }
    }

    private function buildPattern(?string $suffix): string
    {
        return "{$this->directory}/*{$this->buildSuffix($suffix)}";
    }

    private function buildSuffix(?string $suffix): string
    {
        if (null === $suffix) {
            $result = '.php';
        } else {
            $result = ".$suffix.php";
        }
        return $result;
    }

    private function loadFile(string $file)
    {
        return require $file;
    }

    public function get(string $key, $default = null)
    {
        return $this->getRecursiveValue($this->data, $key) ?? $default;
    }

    private function getRecursiveValue($data, $keyPath)
    {
        if (!is_array($keyPath)) {
            $keyPath = explode('.', $keyPath);
        }
        if (count($keyPath) == 0) {
            return $data;
        }
        $key = array_shift($keyPath);

        return $this->getRecursiveValue($data[$key] ?? null, $keyPath);
    }
}
