<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block\View;

use Blue\Core\Application\Ingress\IngressRoute;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;

/**
 * @property IngressRoute $snapp
 */
class BlockAddView extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => '{basePath}/blocks/add/' . $this->snapp->getCode(),
                'content' => [
                    Textfield::class => [
                        'name' => 'code',
                        'placeholder' => 'Code to add',
                        'required' => true
                    ],
                    SubmitButton::class => [
                        'icon' => 'plus',
                        'text' => 'Add block',
                    ],
                ],
            ],
        ];
    }
}
