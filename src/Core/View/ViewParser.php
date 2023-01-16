<?php

declare(strict_types=1);

namespace Blue\Core\View;

use IvoPetkov\HTML5DOMDocument;
use IvoPetkov\HTML5DOMElement;

class ViewParser
{
    public function parseString(string $content): array
    {
        if ($content == '') {
            return [];
        }
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($content);
        $body = $dom->querySelector('body');
        $result = $this->nodeToArray($body)['body'] ?? '';
        if (is_string($result)) {
            $result = [$result];
        }
        return $result;
    }


    private function nodeToArray(HTML5DOMElement $node): array
    {
        $children = [];
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                if ($childNode instanceof HTML5DOMElement) {
                    $children[] = $this->nodeToArray($childNode);
                } else {
                    $children[] = $childNode->textContent;
                }
            }
        }
        if (!empty($children)) {
            return [$node->tagName . $this->attribtuesToString($node->attributes) => $children];
        } elseif ($node->textContent == '') {
            return [$node->outerHTML];
        } else {
            return [$node->tagName . $this->attribtuesToString($node->attributes) => $node->textContent];
        }
    }

    private function attribtuesToString(\DOMNamedNodeMap $attributes)
    {
        $result = '';
        /** @var \DOMAttr $attribute */
        foreach ($attributes as $attribute) {
            $result .= ' ' . $attribute->name . '="' . $attribute->value . '"';
        }
        return $result;
    }
}
