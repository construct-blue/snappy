<?php

declare(strict_types=1);

namespace Blue\Cms\Page;

use Blue\Core\Database\ObjectStorage;
use Blue\Core\Http\Status;
use Generator;

class PageRepository
{
    private ObjectStorage $storage;

    public function __construct(?string $snapp)
    {
        $this->storage = new ObjectStorage(Page::class, $snapp ?? '', 'page');
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
        return $this->storage->loadByCode($code);
    }

    public function save(Page $page): bool
    {
        if ($page->getCode() && $this->findByCode($page->getCode())->getId() !== $page->getId()) {
            throw new PageException('Page already exists', Status::RUNTIME_ERROR);
        }
        return $this->storage->save($page, $page->getId(), $page->getCode());
    }

    public function delete(string $id): bool
    {
        return $this->storage->delete($id);
    }

    public function existsByCode(string $code): bool
    {
        return $this->storage->existsByCode($code);
    }
}
