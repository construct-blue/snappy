<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use Blue\Core\Util\Exception\FileNotFoundException;
use Blue\Core\Util\Exception\FileReadException;
use JsonException;
use JsonSerializable;
use stdClass;

class Json
{
    use UtilClassTrait;

    public const DEFAULT_DEPTH = 512;

    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @return stdClass|array
     * @throws JsonException
     */
    public static function decode(string $json, bool $assoc = false, int $depth = self::DEFAULT_DEPTH): stdClass|array
    {
        return json_decode($json, $assoc, $depth, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $json
     * @param int $depth
     * @return array
     * @throws JsonException
     */
    public static function decodeAssoc(string $json, int $depth = self::DEFAULT_DEPTH): array
    {
        return self::decode($json, true, $depth);
    }

    public static function decodeFileAssoc(string $filename, int $depth = self::DEFAULT_DEPTH)
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException("File not found: $filename");
        }

        $json = @file_get_contents($filename);
        $error = error_get_last();
        if (isset($error['message'])) {
            error_clear_last();
            throw new FileReadException($error['message'] .  " file '$filename'");
        }
        try {
            return self::decodeAssoc($json, $depth);
        } catch (JsonException $exception) {
            throw new JsonException($exception->getMessage() . " in '$filename'");
        }
    }

    public static function encodeAssic(array $data, int $depth = self::DEFAULT_DEPTH): string
    {
        return self::encode($data, $depth);
    }

    public static function encode(JsonSerializable|array $data, int $depth = self::DEFAULT_DEPTH): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR, $depth);
    }
}
