<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Brick\VarExporter\VarExporter;
use Generator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionObject;

class EntrypointHelper
{
    public const FILE = 'public/static/entrypoints.json';
    public const FILE_PHP = 'data/entrypoints.php';

    protected array $data = [];
    protected array $enabled = [];

    public function __construct(private readonly string $basePath = '')
    {
        $this->clear();
    }

    public function build(): void
    {
        $this->load();
        file_put_contents(
            $this->basePath . self::FILE_PHP,
            '<?php ' . VarExporter::export($this->data, VarExporter::ADD_RETURN)
        );
    }

    public function enableComponent(ViewComponentInterface $component)
    {
        $this->enableObject($component);
    }

    public function enableObject(object $object)
    {
        foreach ($this->findFiles(new ReflectionObject($object)) as $file) {
            $this->enable($file);
        }
    }

    private function findFiles(ReflectionClass $reflection): Generator
    {
        if ($reflection->getParentClass()) {
            yield from $this->findFiles($reflection->getParentClass());
        }
        $attributes = $reflection->getAttributes(Entrypoint::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            yield $attribute->newInstance()->file;
        }
    }

    public function clear(): void
    {
        $this->enabled = [];
    }

    public function enable(string $entrypoint)
    {
        $this->enabled[] = self::buildEntrypointName(self::buildEntrypoint($entrypoint));
    }

    public static function buildEntrypoint(string $entrypoint)
    {
        return '.' . str_replace(getcwd(), '', $entrypoint);
    }

    public static function buildEntrypointName(string $entrypoint)
    {
        $result = strtolower(str_replace(['./src/', '/', '.ts'], ['', '_', ''], $entrypoint));
        return implode('_', array_unique(explode('_', $result)));
    }

    public function load()
    {
        if (empty($this->data)) {
            $data = @include $this->basePath . self::FILE_PHP;
            if (!empty($data)) {
                $this->data = $data;
            } else {
                $this->data = json_decode(file_get_contents($this->basePath . self::FILE), true);
            }
        }
    }

    public function dumpFiles(string $key)
    {
        $this->load();
        $result = [];
        foreach ($this->enabled as $viewEntrypoint) {
            if (isset($this->data['entrypoints'][$viewEntrypoint][$key])) {
                $files = $this->data['entrypoints'][$viewEntrypoint][$key];
                foreach ($files as $file) {
                    $result[$file] = $file;
                }
            }
        }
        return array_values($result);
    }


    public function dumpCss()
    {
        $result = '';
        foreach ($this->dumpFiles('css') as $dumpFile) {
            $result .= "<link class='css' rel='stylesheet' href='$dumpFile'>";
        }
        return $result;
    }

    public function dumpJs()
    {
        $result = '';
        foreach ($this->dumpFiles('js') as $dumpFile) {
            $result .= "<script class='script' defer src='$dumpFile'></script>";
        }
        return $result;
    }
}
