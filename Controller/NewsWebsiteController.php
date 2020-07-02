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

use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Bundle\WebsiteBundle\Resolver\TemplateAttributeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

/**
 * Class NewsWebsiteController.
 */
class NewsWebsiteController extends AbstractController
{
    /**
     * @var MediaManagerInterface
     */
    private $mediaManager;

    public function __construct(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function indexAction(int $id, Request $request): Response
    {
        $news = $this->get(NewsRepository::class)->findById($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }

        $media = $this->mediaManager->getById($news->getHeader()->getId(), 'de');
        $news->setHeader($media);

        return $this->render(
            'news/index.html.twig',
            $this->get(TemplateAttributeResolverInterface::class)->resolve(
                [
                    'news' => $news,
                    'success' => $request->query->get('success'),
                    'content' => ['title' => $news->getTitle()],
                ]
            )
        );
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                NewsRepository::class,
                TemplateAttributeResolverInterface::class,
            ]
        );
    }
}
