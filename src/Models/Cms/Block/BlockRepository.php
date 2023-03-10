<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Block;

use Blue\Core\Database\Exception\ObjectLoadingException;
use Blue\Core\Database\ObjectStorage;
use Blue\Core\Database\Serializer\StorableSerializer;
use Blue\Core\Database\StorableObjectStorage;
use Generator;

class BlockRepository
{
    private StorableObjectStorage $storage;

    public function __construct(private ?string $snapp)
    {
        $this->storage = new StorableObjectStorage(Block::class, $snapp ?? '', 'block');
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
        return $this->storage->loadByCode($this->snapp . $code);
    }

    public function save(
        Block $block
    ): bool {
        if (
            $block->getCode() !== null
            && $this->existsByCode($block->getCode())
            && $this->findByCode($block->getCode())->getId() !== $block->getId()
        ) {
            throw BlockException::forValidation('code', 'Block already exists');
        }
        return $this->storage->save($block, $block->getId(), $this->snapp . $block->getCode());
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
