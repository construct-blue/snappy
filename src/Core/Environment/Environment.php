<?php

declare(strict_types=1);

namespace Blue\Core\Environment;

use Blue\Core\Environment\Exception\MissingRequiredValueException;
use Blue\Core\Util\SingletonTrait;
use Error;

use function is_array;
use function array_shift;
use function count;
use function explode;

class Environment
{
    use SingletonTrait;

    private array $data = [];

    private string $root;

    protected function onConstruct(): void
    {
        $this->root = getcwd();
        try {
            $this->data = include 'env.php';
        } catch (Error $error) {
            include 'src/setup.php';
            exit;
        }
    }

    public function getDevDomain(): string
    {
        return (string)$this->get('dev_domain', '');
    }

    public function isDevMode(): bool
    {
        return (bool)$this->get('dev_mode', false);
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     * @return Environment
     */
    public function setRoot(string $root): Environment
    {
        $this->root = $root;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Environment
     */
    public function setData(array $data): Environment
    {
        $this->data = $data;
        return $this;
    }

    public function get(string $key, $default = null, bool $required = false)
    {
        $configValue = $this->getRecursiveValue($this->getData(), $key);
        if ($required && null === $configValue) {
            throw new MissingRequiredValueException("for key '$key'");
        }
        return $configValue ?? $default;
    }

    public function getRootPath(): string
    {
        return $this->getRoot();
    }

    public function getFilepath(string $key, $default = null, bool $required = true): string
    {
        $configValue = $this->get($key, $default, $required);
        return $this->getRoot() . DIRECTORY_SEPARATOR . ltrim($configValue, DIRECTORY_SEPARATOR);
    }

    private function getRecursiveValue($data, $keyPath)
    {
        if (!is_array($keyPath)) {
            $keyPath = explode('.', $keyPath);
        }
        if (count($keyPath) == 0) {
            return $data;
        }
        $key = array_shift($keyPath);

        return $this->getRecursiveValue($data[$key] ?? null, $keyPath);
    }
}
