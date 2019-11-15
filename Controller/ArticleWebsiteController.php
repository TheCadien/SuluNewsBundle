<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Controller;

use App\Bundle\ArticleBundle\Repository\ArticleRepository;
use Sulu\Bundle\WebsiteBundle\Resolver\TemplateAttributeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArticleWebsiteController
 * @package App\Bundle\ArticleBundle\Controller
 */
class ArticleWebsiteController extends AbstractController
{

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function indexAction(int $id, Request $request): Response
    {
        $article = $this->get(ArticleRepository::class)->findById($id);
        if (!$article) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'articles/index.html.twig',
            $this->get(TemplateAttributeResolverInterface::class)->resolve(
                [
                    'article' => $article,
                    'success' => $request->query->get('success'),
                    'content' => ['title' => $article->getTitle()],
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
                ArticleRepository::class,
                TemplateAttributeResolverInterface::class,
            ]
        );
    }
}
