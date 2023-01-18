<?php

declare(strict_types=1);

namespace Blue\Cms\Block;

use Blue\Core\Database\Exception\ObjectLoadingException;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Http\Status;
use Generator;

class BlockRepository
{
    private ObjectStorage $storage;

    public function __construct(?string $snapp)
    {
        $this->storage = new ObjectStorage(Block::class, $snapp ?? '', 'block');
    }

    /**
     * @return Generator&iterable<Block>
     */
    public function findAll(): Generator
    {
        return $this->storage->loadAll();
    }

    /**
     * @param string $id
     * @return Block
     * @throws ObjectLoadingException
     */
    public function findById(
        string $id
    ): Block {
        return $this->storage->loadById($id);
    }

    public function findByCode(string $code): Block
    {
        return $this->storage->loadByCode($code);
    }

    public function save(
        Block $block
    ): bool {
        if ($block->getCode() && $this->existsByCode($block->getCode())) {
            if ($this->findByCode($block->getCode())->getId() !== $block->getId()) {
                throw new BlockException('Block already exists', Status::RUNTIME_ERROR);
            }
        }
        return $this->storage->save($block, $block->getId(), $block->getCode());
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
