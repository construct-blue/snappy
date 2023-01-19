<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;

class PageAddView extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => '{basePath}/pages/add/{snapp}',
                'content' => [
                    Textfield::class => [
                        'name' => 'code',
                        'placeholder' => 'Path to add',
                        'required' => true,
                    ],
                    SubmitButton::class => [
                        'icon' => 'plus',
                        'text' => 'Add page',
                    ],
                ],
            ],
        ];
    }
}
