<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Block;

class BlockCreator
{
    public function __construct(private readonly BlockRepository $repository)
    {
    }

    public function createMissing(string $content): array
    {
        $created = [];
        if (preg_match_all('/{\w+:(?<codes>\w+)}/', $content, $matches)) {
            $codes = $matches['codes'] ?? [];
            foreach ($codes as $code) {
                if (!$this->repository->existsByCode($code)) {
                    $block = new Block();
                    $block->setCode($code);
                    $this->repository->save($block);
                    $created[] = $code;
                }
            }
        }
        return $created;
    }
}
