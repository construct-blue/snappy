<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Page;

use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StorableSerializer;
use Blue\Core\Database\StorableObjectStorage;
use Generator;

class PageRepository
{
    private StorableObjectStorage $storage;

    public function __construct(private ?string $snapp)
    {
        $this->storage = new StorableObjectStorage(Page::class, $snapp ?? '', 'page');
    }

    /**
     * @return Generator&iterable<Page>
     */
    public function findAll(): Generator
    {
        return $this->storage->loadAll();
    }

    public function findById(string $id): Page
    {
        return $this->storage->loadById($id);
    }

    public function findByCode(string $code): Page
    {
        return $this->storage->loadByCode($this->snapp . $code);
    }

    public function save(Page $page): bool
    {
        if (
            $page->getCode() !== null
            && $this->existsByCode($page->getCode())
            && $this->findByCode($page->getCode())->getId() !== $page->getId()
        ) {
            throw PageException::forValidation('code', 'Page already exists');
        }
        return $this->storage->save($page, $page->getId(), $this->snapp . $page->getCode());
    }

    public function delete(string $id): bool
    {
        return $this->storage->delete($id);
    }

    public function existsByCode(string $code): bool
    {
        return $this->storage->existsByCode($this->snapp . $code);
    }
}
