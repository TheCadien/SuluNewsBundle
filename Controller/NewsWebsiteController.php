<?php

declare(strict_types=1);

namespace App\Bundle\NewsBundle\Controller;

use App\Bundle\NewsBundle\Repository\NewsRepository;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Bundle\WebsiteBundle\Resolver\TemplateAttributeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NewsWebsiteController
 * @package App\Bundle\NewsBundle\Controller
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

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function indexAction(int $id, Request $request): Response
    {
        $news = $this->get(NewsRepository::class)->findById($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }

        $media = $this->mediaManager->getById($news->getHeader()->getId(),'de');
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

    /**
     * @return array
     */
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
