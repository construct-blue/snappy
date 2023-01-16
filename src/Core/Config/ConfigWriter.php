<?php

declare(strict_types=1);

namespace Blue\Core\Config;

use Brick\VarExporter\VarExporter;

use function array_replace_recursive;
use function array_shift;
use function explode;
use function file_put_contents;
use function is_array;
use function is_dir;
use function mkdir;

use const DIRECTORY_SEPARATOR;

class ConfigWriter
{
    private Config $config;

    private array $data = [];

    final public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function set(string $key, $value): self
    {
        if (str_contains($key, '.')) {
            $data = $this->setRecursiveValue($value, $key);
            $this->data = array_replace_recursive($this->data, $data);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    private function setRecursiveValue($data, $keyPath)
    {
        if (!is_array($keyPath)) {
            $keyPath = explode('.', $keyPath);
        }
        $key = array_shift($keyPath);
        if ($key) {
            $data = $this->setRecursiveValue($data, $keyPath);
            $data = [$key => $data];
        }
        return $data;
    }

    public function write(): void
    {
        $dir = $this->config->getDirectory();
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $exportOptions = VarExporter::ADD_RETURN | VarExporter::ADD_TYPE_HINTS | VarExporter::TRAILING_COMMA_IN_ARRAY;
        foreach ($this->data as $key => $datum) {
            $currentDatum = $this->config->get($key);
            if (is_array($datum) && is_array($currentDatum)) {
                $datum = array_replace_recursive($currentDatum, $datum);
            }
            foreach ($this->config->getSuffixList() as $suffix) {
                $filename = $dir . DIRECTORY_SEPARATOR . "$key.$suffix.php";
                file_put_contents(
                    $filename,
                    "<?php\n\ndeclare(strict_types=1);\n\n" . VarExporter::export($datum, $exportOptions)
                );
            }
        }
    }
}
