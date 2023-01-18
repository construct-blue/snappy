<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Block\View;

use Blue\Core\View\Component\Button\ConfirmButton;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Hidden;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;

/**
 * @property string $id
 * @property string $code
 * @property array $content
 */
class BlockEditView extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => '{basePath}/blocks/save/{snapp}',
                'id' => '',
                'content' => [
                    Hidden::class => [
                        'name' => 'id',
                        'value' => $this->id,
                    ],
                    'p' => [
                        Textfield::fromParams([
                            'name' => 'code',
                            'value' => '{code}',
                            'required' => true,
                        ]),
                        SubmitButton::class => [
                            'icon' => 'save',
                            'text' => 'Save'
                        ],
                        ConfirmButton::class => [
                            'text' => 'Delete',
                            'icon' => 'trash-2',
                            'message' => 'Sure?',
                            'type' => 'submit',
                            'formaction' => '{basePath}/blocks/delete/{snapp}',
                        ],
                    ],
                    'div role="input" contenteditable name="content"' => $this->content,
                ]
            ],
        ];
    }
}
