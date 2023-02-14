<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\Environment\Environment;
use Blue\Core\Util\ArrayFile;
use Blue\Core\Util\AttributeReflector;
use Blue\Core\Util\Exception\FileNotFoundException;
use Blue\Core\Util\Exception\FileReadException;
use Blue\Core\Util\Json;
use Blue\Core\View\Exception\InvalidStaticResourceFileException;
use JsonException;
use ReflectionException;

class ClientResources
{
    public const DEFAULT_CACHE_FILE = 'cache/resources.cache';
    public const DEFAULT_RESOURCE_FILE = '/public/static/entrypoints.json';
    public const DEFAULT_ENTRYPOINTS_FILE = '/entrypoints.json';
    public const CONFIG_KEY_RESOURCES = 'resources';
    private const ENTRYPOINTS = 'entrypoints';

    private array $componentClasses = [];
    private array $fileKeys = [];
    private array $filesMap;

    private string $projectRoot;

    public function __construct(Environment $env)
    {
        $this->projectRoot = $env->getRootPath();
        $resourceFile = $env->getFilepath(
            self::CONFIG_KEY_RESOURCES,
            self::DEFAULT_RESOURCE_FILE,
            false
        );

        $cache = @include self::DEFAULT_CACHE_FILE;
        if (false === $cache) {
            error_clear_last();
        } elseif (is_array($cache)) {
            $this->filesMap = $cache;
        }

        if (!isset($this->filesMap)) {
            try {
                $staticFilesData = Json::decodeFileAssoc($resourceFile);
            } catch (JsonException | FileNotFoundException | FileReadException $exception) {
                throw InvalidStaticResourceFileException::from($exception);
            }
            if (!isset($staticFilesData[self::ENTRYPOINTS]) || !is_array($staticFilesData[self::ENTRYPOINTS])) {
                throw new InvalidStaticResourceFileException(
                    sprintf("Key '%s' must be array in: %s", self::ENTRYPOINTS, $resourceFile)
                );
            }
            $this->filesMap = $staticFilesData[self::ENTRYPOINTS];

            if (!$env->isDevMode()) {
                ArrayFile::write(self::DEFAULT_CACHE_FILE, $this->filesMap);
            }
        }
    }

    public function import(Import $file): self
    {
        $key = $file->getKey($this->projectRoot);
        if (!isset($this->fileKeys[$key])) {
            $this->fileKeys[$key] = true;
        }
        return $this;
    }

    /**
     *
     * hot method: called for each rendered component
     *
     * @param ViewComponentInterface $component
     * @return $this
     * @throws ReflectionException
     */
    public function importComponent(ViewComponentInterface $component): self
    {
        $class = get_class($component);
        if (!isset($this->componentClasses[$class])) {
            $this->componentClasses[$class] = true;
            /** @var Import $attribute */
            foreach (
                AttributeReflector::getAttributes(
                    $class,
                    Import::class,
                    0,
                    $this->componentClasses
                ) as $attribute
            ) {
                $this->import($attribute);
            }
        }
        return $this;
    }

    public function getFiles(ResourceType $type): array
    {
        $result = [];
        foreach ($this->fileKeys as $fileKey => $v) {
            if (isset($this->filesMap[$fileKey][$type->name]) && is_array($this->filesMap[$fileKey][$type->name])) {
                foreach ($this->filesMap[$fileKey][$type->name] as $file) {
                    if (!isset($result[$file])) {
                        $result[$file] = true;
                    }
                }
            }
        }

        return array_keys($result);
    }

    public function getJSFiles(): array
    {
        return $this->getFiles(ResourceType::js);
    }

    public function getCSSFiles(): array
    {
        return $this->getFiles(ResourceType::css);
    }
}
