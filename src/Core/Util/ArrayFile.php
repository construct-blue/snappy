<?php

declare(strict_types=1);

namespace Blue\Core\Util;

use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use ValueError;

class ArrayFile
{
    use UtilClassTrait;

    /**
     * @param string $filename
     * @param array $data
     * @return void
     * @throws Exception\ArrayFileWriteException
     */
    public static function write(string $filename, array $data): void
    {
        try {
            $body = VarExporter::export($data, VarExporter::ADD_RETURN);
            file_put_contents($filename, "<?php $body");
        } catch (ExportException | ValueError $exception) {
            throw Exception\ArrayFileWriteException::from($exception);
        }
    }

    /**
     * @param string $source
     * @param string $target
     * @return void
     * @throws Exception\ArrayFileWriteException
     * @throws Exception\FileNotFoundException
     */
    public static function writeFromJsonFile(string $source, string $target): void
    {
        self::write($target, Json::decodeFileAssoc($source));
    }
}
