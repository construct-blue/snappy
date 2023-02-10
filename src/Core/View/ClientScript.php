<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ClientScript
{
    public function __construct(public string $file)
    {
    }

    public function getKey(string $projectRoot): string
    {
        $explodedPath = explode('/', $this->getProjectPath($projectRoot));
        $pathToImplode = [];

        foreach ($explodedPath as $pathItem) {
            if ($pathItem === '.') {
                continue;
            }
            if (str_contains($pathItem, '.')) {
                $pathItem = substr($pathItem, 0, strrpos($pathItem, '.'));
            }
            if (end($pathToImplode) !== $pathItem) {
                $pathToImplode[] = $pathItem;
            }
        }

        return strtolower(implode('_', $pathToImplode));
    }

    public function getProjectPath(string $projectRoot): string
    {
        return '.' . str_replace($projectRoot, '', $this->file);
    }
}
