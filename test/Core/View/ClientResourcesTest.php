<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\Environment\Environment;
use Blue\Core\View\Import;
use Blue\Core\View\ClientResources;
use Blue\Core\View\Exception\InvalidStaticResourceFileException;
use PHPUnit\Framework\TestCase;

class ClientResourcesTest extends TestCase
{
    public function testShouldExportJSFilesInImportOrder()
    {
        $env = clone Environment::instance();
        $env->setData([
            "resources" => "/Core/View/valid_resource_config/resources.json",
            "entrypoints" => "/Core/View/valid_resource_config/entrypoints.json",
            "dev_mode" => true
        ]);
        $resources = new ClientResources($env);
        $resources->import(new Import(__DIR__ . '/script3.ts'));
        $resources->import(new Import(__DIR__ . '/script1.ts'));
        $resources->import(new Import(__DIR__ . '/script2.ts'));

        $this->assertEquals(
            [
                '/static/runtime.js',
                '/static/core_view_script3.js',
                '/static/core_view_script1.js',
                '/static/core_view_script2.js',
            ],
            $resources->getJSFiles(),
        );
    }

    public function testShouldImportClientScriptsFromComponent()
    {
        $env = clone Environment::instance();
        $env->setData([
            "resources" => "/Core/View/valid_resource_config/resources.json",
            "entrypoints" => "/Core/View/valid_resource_config/entrypoints.json",
            "dev_mode" => true
        ]);
        $resources = new ClientResources($env);
        $resources->importComponent(ClientResourceTestComponent::new());
        $this->assertEquals(
            [
                '/static/runtime.js',
                '/static/core_view_script2.js',
            ],
            $resources->getJSFiles(),
        );
    }

    public function testShouldThrowExceptionForInvalidResourceFile()
    {
        $this->expectException(InvalidStaticResourceFileException::class);
        $env = clone Environment::instance();
        $env->setData([
            "resources" => "/Core/View/invalid_resource_config/resources.json",
            "entrypoints" => "/Core/View/invalid_resource_config/entrypoints.json",
            "dev_mode" => true
        ]);
        new ClientResources($env);
    }

    public function testShouldThrowExceptionForMissingResourceFile()
    {
        $this->expectException(InvalidStaticResourceFileException::class);
        $env = clone Environment::instance();
        $env->setData([
            "resources" => "/Core/View/missing_resource_config/resources.json",
            "entrypoints" => "/Core/View/missing_resource_config/entrypoints.json",
            "dev_mode" => true
        ]);
        new ClientResources($env);
    }
}
