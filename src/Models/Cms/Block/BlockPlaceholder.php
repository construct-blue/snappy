<?php

declare(strict_types=1);

namespace Blue\Models\Cms\Block;

use Laminas\Diactoros\CallbackStream;
use ParsedownExtra;
use Psr\Http\Message\StreamInterface;

class BlockPlaceholder
{
    public function __construct(private readonly BlockRepository $repository)
    {
    }

    public function replace(string|StreamInterface $str): string|StreamInterface
    {
        if ($str instanceof CallbackStream) {
            return new CallbackStream(fn() => $this->replace($str->getContents()));
        }
        $parsedown = new ParsedownExtra();
        $blocks = $this->repository->findAll();
        foreach ($blocks as $block) {
            $str = str_replace("{block:{$block->getCode()}}", $parsedown->text($block->getContent()), $str);
        }
        return $str;
    }
}
