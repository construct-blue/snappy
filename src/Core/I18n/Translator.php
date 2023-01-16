<?php

declare(strict_types=1);

namespace Blue\Core\I18n;

use Locale;

use const DIRECTORY_SEPARATOR;

class Translator
{
    /**
     * @var Translator[]
     */
    protected static array $instances = [];
    protected array $files = [];
    protected array $paths = [];
    protected string $locale;

    final private function __construct(string $locale)
    {
        $this->locale = $locale;
        $this->addPath(__DIR__ . '/translations');
    }

    public static function instance(string $locale): self
    {
        if (!isset(self::$instances[$locale])) {
            self::$instances[$locale] = new static($locale);
        }
        return self::$instances[$locale];
    }

    public function translate(string $code): string
    {
        $translations = $this->loadTranslations();
        return $translations[$code] ?? $code;
    }

    public function translatepl(string $code, int $count): string
    {
        $translations = $this->loadTranslations();
        for ($i = $count; $i >= 0; $i--) {
            if (isset($translations["$code.$i"])) {
                return $translations["$code.$i"] ?? $code;
            }
        }
        return $translations["$code.$i"] ?? $code;
    }

    public function loadTranslations(): array
    {
        foreach ($this->paths as $path) {
            $locale = $this->locale;
            $language = Locale::getPrimaryLanguage($locale);
            $this->addFile($path . DIRECTORY_SEPARATOR . $language . '.php');
            $this->addFile($path . DIRECTORY_SEPARATOR . $locale . '.php');
        }
        $translations = [];
        foreach ($this->files as $file) {
            $data = include $file;
            if (is_array($data)) {
                $translations = array_replace($translations, $data);
            }
        }
        return $translations;
    }

    public function addPath(string $path): static
    {
        $this->paths[$path] = $path;
        return $this;
    }

    public function addFile(string $file): static
    {
        if (!isset($this->files[$file]) && file_exists($file)) {
            $this->files[$file] = $file;
        }
        return $this;
    }
}
