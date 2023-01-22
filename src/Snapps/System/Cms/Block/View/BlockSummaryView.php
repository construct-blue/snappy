<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block\View;

use Blue\Core\View\ViewComponent;

/**
 * @property string $code
 */
class BlockSummaryView extends ViewComponent
{
    public function render(): array
    {
        return [
            'span style="font-family: Markdown; background: var(--bg)"' => "{block:$this->code}",
        ];
    }
}
