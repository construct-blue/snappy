<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use RecursiveIteratorIterator;

use function is_array;
use function is_object;
use function preg_match_all;
use function str_replace;

class PlaceholderHelper
{
    use UtilClassTrait;

    /**
     * @param string $str
     * @return array
     */
    public static function findPlaceholderResolved(string $str): array
    {
        $result = [];
        $placeholders = self::findPlaceholder($str);
        $it = new RecursiveIteratorIterator(new \RecursiveArrayIterator($placeholders));
        foreach ($it as $value) {
            $result[$value] = self::removeDelimiter($value);
        }
        return $result;
    }

    public static function removeDelimiter(string $placeholder): string
    {
        $replace = [
            '{' => '',
            '}' => '',
        ];
        return str_replace(array_keys($replace), array_values($replace), $placeholder);
    }

    /**
     * @param string $str
     * @return array
     */
    public static function findPlaceholder(string $str): array
    {
        $matches = [];
        preg_match_all('/{\w+}/', $str, $matches);
        return $matches;
    }

    public static function replacePlaceholder(string $str, object|array $data): string
    {
        $placeholder = self::findPlaceholderResolved($str);
        $isArray = is_array($data);
        $isObject = is_object($data);
        foreach ($placeholder as $pl => $key) {
            $value = null;
            if ($isArray) {
                $value = (string)($data[$key] ?? '');
            }
            if ($isObject && $key) {
                $value = (string)($data->$key ?? '');
            }
            if (null !== $value) {
                $str = str_replace($pl, $value, $str);
            }
        }
        return $str;
    }
}
