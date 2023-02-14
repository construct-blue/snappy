<?php

declare(strict_types=1);

namespace Blue\Developer\Build;

use Blue\Core\Environment\Environment;
use Blue\Core\View\ClientResources;
use Blue\Core\View\Import;
use ReflectionClass;
use Throwable;

use function class_exists;
use function file_put_contents;
use function json_encode;

class EntrypointsFinder
{
    public function execute(): void
    {
        $env = Environment::instance();
        $root = $env->getRootPath();
        $classes = $this->findClasses($root . '/src');

        $entrypoints = [];
        foreach ($classes as $class) {
            try {
                if (@class_exists($class)) {
                    $reflector = new ReflectionClass($class);
                    $attributes = $reflector->getAttributes(Import::class);
                    foreach ($attributes as $attribute) {
                        /** @var Import $entrypoint */
                        $entrypoint = $attribute->newInstance();
                        $entrypoints[$entrypoint->getKey($root)] = $entrypoint->getProjectPath($root);
                    }
                }
            } catch (Throwable $throwable) {
                echo $throwable->getMessage();
                echo "\n";
            }
        }
        $json = json_encode($entrypoints);
        $filename = $env->getFilepath('entrypoints', ClientResources::DEFAULT_ENTRYPOINTS_FILE, false);
        file_put_contents($filename, $json);

        $count = (string)count($entrypoints);
        echo "Written $count entrypoints to $filename.\n";
    }


    private function findClasses(string $path): array
    {
        $classes = [];
        $iti = new \RecursiveDirectoryIterator($path);
        $scanner = new TokenScanner();
        foreach (new \RecursiveIteratorIterator($iti) as $file) {
            $file = (string)$file;
            if (str_ends_with($file, '.php')) {
                try {
                    $class = @$scanner->getClassNameFromFile($file);
                    if ($class) {
                        $classes[] = $class;
                    }
                } catch (Throwable $throwable) {
                    echo $throwable->getMessage();
                    echo "\n";
                }
            }
        }
        return $classes;
    }
}
