<?php

declare(strict_types=1);

/*
 * This file is part of TheCadien/SuluNewsBundle.
 *
 * (c) Oliver Kossin
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TheCadien\Bundle\SuluNewsBundle\Controller;

use Sulu\Bundle\PreviewBundle\Preview\Preview;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * Class NewsWebsiteController.
 */
class NewsWebsiteController extends AbstractController
{
    public function indexAction(News $news, $preview = null): Response
    {
        if (!$news) {
            throw new NotFoundHttpException();
        }

        // TODO !
        if ($preview) {
            $content = $this->renderPreview('news/index.html.twig', ['news' => $news]);
        } else {
            $content = $this->renderView(
                'news/index.html.twig',
                ['news' => $news]
            );
        }

        return new Response($content);
    }

    protected function renderPreview(string $view, array $parameters = []): string
    {
        $parameters['previewParentTemplate'] = $view;
        $parameters['previewContentReplacer'] = Preview::CONTENT_REPLACER;

        return $this->renderView('@SuluWebsite/Preview/preview.html.twig', $parameters);
    }
}
