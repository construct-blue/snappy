<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use ReflectionClass;
use ReflectionException;

class AttributeReflector
{
    use UtilClassTrait;


    /**
     * @template T of object
     *
     * @param class-string $className
     * @param class-string<T>|null $attributeClass
     * @param int $flags
     * @return T[]
     * @throws ReflectionException
     *
     */
    public static function getAttributes(string $className, ?string $attributeClass = null, int $flags = 0): array
    {
        $attributes = [];
        $reflection = new ReflectionClass($className);
        if ($reflection->getParentClass()) {
            $attributes = self::getAttributes($reflection->getParentClass()->getName(), $attributeClass, $flags);
        }
        foreach ($reflection->getAttributes($attributeClass, $flags) as $attribute) {
            $attributes[] = $attribute->newInstance();
        }
        return $attributes;
    }
}
