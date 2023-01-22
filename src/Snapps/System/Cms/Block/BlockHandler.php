<?php

declare(strict_types=1);

namespace Blue\Snapps\System\Cms\Block;

use Blue\Models\Cms\Block\BlockRepository;
use Blue\Snapps\System\Cms\Block\View\BlockView;
use Blue\Snapps\System\Cms\TemplateHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlockHandler extends TemplateHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $snappCode = $request->getAttribute('snapp');
        $uriBuilder = $this->getUriBuilder($request);
        $this->assign('cmsPath', (string) $uriBuilder->withRoute('blocks', ['snapp' => $snappCode]));
        $repo = new BlockRepository($request->getAttribute('snapp'));
        $blocks = iterator_to_array($repo->findAll());
        return new HtmlResponse(
            $this->render(BlockView::class, ['blocks' => $blocks])
        );
    }
}
