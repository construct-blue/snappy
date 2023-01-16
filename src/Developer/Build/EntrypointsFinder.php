<?php

declare(strict_types=1);

namespace Blue\Developer\Build;

use Blue\Core\View\Entrypoint;
use Blue\Core\View\EntrypointHelper;
use Throwable;

use function class_exists;
use function file_put_contents;
use function getcwd;
use function is_subclass_of;
use function json_encode;

class EntrypointsFinder
{
    public function execute()
    {
        $classes = $this->findClasses();

        $entrypoints = [];
        foreach ($classes as $class) {
            try {
                if (@class_exists($class)) {
                    $reflector = new \ReflectionClass($class);
                    $attributes = $reflector->getAttributes(Entrypoint::class);
                    foreach ($attributes as $attribute) {
                        $entrypoint = $attribute->newInstance()->file;
                        $entrypoint = EntrypointHelper::buildEntrypoint($entrypoint);
                        $entrypoints[EntrypointHelper::buildEntrypointName($entrypoint)] = $entrypoint;
                    }
                }
            } catch (Throwable $throwable) {
                echo $throwable->getMessage();
                echo "\n";
            }
        }
        $json = json_encode($entrypoints);
        $filename = 'entrypoints.json';
        file_put_contents($filename, $json);

        $count = (string)count($entrypoints);
        echo "Written $count entrypoints to $filename.\n";
    }


    private function findClasses(): array
    {
        $classes = [];
        $iti = new \RecursiveDirectoryIterator(getcwd() . '/src');
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
