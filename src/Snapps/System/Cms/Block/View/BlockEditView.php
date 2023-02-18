<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block\View;

use Blue\Core\Application\Snapp\SnappRoute;
use Blue\Core\View\Component\Button\ConfirmButton;
use Blue\Core\View\Component\Button\SubmitButton;
use Blue\Core\View\Component\Form\Form;
use Blue\Core\View\Component\Form\Hidden;
use Blue\Core\View\Component\Form\Markdown;
use Blue\Core\View\Component\Form\Textfield;
use Blue\Core\View\ViewComponent;

/**
 * @property string $cmsBasePath
 * @property string $id
 * @property string $code
 * @property array $content
 * @property SnappRoute $snapp
 */
class BlockEditView extends ViewComponent
{
    public function render(): array
    {
        return [
            Form::class => [
                'method' => 'post',
                'action' => $this->cmsBasePath . '/save/' . $this->snapp->getCode(),
                'id' => '',
                'content' => [
                    Hidden::class => [
                        'name' => 'id',
                        'value' => $this->id,
                    ],
                    'p' => [
                        Textfield::new([
                            'label' => 'Code',
                            'name' => 'code',
                            'value' => $this->code,
                            'required' => true,
                        ]),
                    ],
                    Markdown::class => [
                        'label' => 'Content',
                        'name' => 'content',
                        'value' =>  $this->content,
                    ],
                    SubmitButton::class => [
                        'icon' => 'save',
                        'text' => 'Save'
                    ],
                    ConfirmButton::class => [
                        'text' => 'Delete',
                        'icon' => 'trash-2',
                        'message' => 'Sure?',
                        'type' => 'submit',
                        'formaction' => $this->cmsBasePath . '/delete/' . $this->snapp->getCode(),
                    ],
                ]
            ],
        ];
    }
}
