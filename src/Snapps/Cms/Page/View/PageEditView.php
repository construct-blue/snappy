<?php

declare(strict_types=1);

namespace Blue\Snapps\Cms\Page\View;

use Blue\Core\View\Component\Button\ConfirmButton;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Hidden;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;

/**
 * @property string $snapp
 * @property string $id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property array $header
 * @property array $main
 * @property array $footer
 */
class PageEditView extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => '{basePath}/pages/save/{snapp}',
                'id' => '',
                'content' => [
                    Hidden::class => [
                        'name' => 'id',
                        'value' => $this->id,
                    ],
                    'p' => [
                        Textfield::class => [
                            'label' => 'Path',
                            'name' => 'code',
                            'required' => true,
                            'value' => $this->code,
                        ],
                        SubmitButton::class => [
                            'icon' => 'save',
                            'text' => 'Save',
                        ],
                        ConfirmButton::class => [
                            'type' => 'submit',
                            'icon' => 'trash-2',
                            'text' => 'Delete',
                            'message' => 'Sure?',
                            'formaction' => '{basePath}/pages/delete/{snapp}'
                        ],
                    ],
                    [
                        'span' => [
                            Textfield::class => [
                                'label' => 'Title',
                                'name' => 'title',
                                'value' => $this->title,
                            ],
                        ],
                    ],
                    [
                        'span' => [
                            Textfield::class => [
                                'label' => 'Description',
                                'name' => 'description',
                                'value' => $this->description,
                            ],
                        ],
                    ],
                    'header contenteditable name="header"' => $this->header,
                    'main contenteditable name="main"' => $this->main,
                    'footer contenteditable name="footer"' => $this->footer,
                ],
            ]
        ];
    }
}