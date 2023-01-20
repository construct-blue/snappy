<?php

declare(strict_types=1);

namespace Blue\Cms\Block;

class BlockPlaceholder
{
    public function __construct(private readonly BlockRepository $repository)
    {
    }

    public function replace(string $str): string
    {
        $parsedown = new \ParsedownExtra();
        $blocks = $this->repository->findAll();
        foreach ($blocks as $block) {
            $str = str_replace("{block:{$block->getCode()}}", $parsedown->text($block->getContent()), $str);
        }
        return $str;
    }
}
