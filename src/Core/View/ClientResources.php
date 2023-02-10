<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\Environment\Environment;
use Blue\Core\Util\AttributeReflector;
use Blue\Core\Util\Exception\FileNotFoundException;
use Blue\Core\Util\Exception\FileReadException;
use Blue\Core\Util\Json;
use Blue\Core\View\Exception\InvalidStaticResourceFileException;
use JsonException;

class ClientResources
{
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
        $resourceFile = $env->getFilepath(self::CONFIG_KEY_RESOURCES, self::DEFAULT_RESOURCE_FILE, false);
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
    }

    public function importClientScript(ClientScript $clientScript): self
    {
        $key = $clientScript->getKey($this->projectRoot);
        if (!in_array($key, $this->fileKeys)) {
            $this->fileKeys[] = $key;
        }
        return $this;
    }

    public function importComponent(ViewComponentInterface $component): self
    {
        $class = get_class($component);
        if (!in_array($class, $this->componentClasses)) {
            /** @var ClientScript $attribute */
            foreach (AttributeReflector::getAttributes($class, ClientScript::class) as $attribute) {
                $this->importClientScript($attribute);
            }
        }
        return $this;
    }

    public function getFiles(ResourceType $type): array
    {
        $result = [];
        foreach ($this->fileKeys as $fileKey) {
            if (isset($this->filesMap[$fileKey][$type->name]) && is_array($this->filesMap[$fileKey][$type->name])) {
                foreach ($this->filesMap[$fileKey][$type->name] as $file) {
                    if (!in_array($file, $result)) {
                        $result[] = $file;
                    }
                }
            }
        }

        return $result;
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
