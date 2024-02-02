<?php

declare(strict_types=1);

/*
 * This file is part of TheCadien/SuluNewsBundle.
 *
 * by Oliver Kossin and contributors.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TheCadien\Bundle\SuluNewsBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use TheCadien\Bundle\SuluNewsBundle\Api\Factory\NewsApiDtoFactory;
use TheCadien\Bundle\SuluNewsBundle\Api\News as NewsApi;
use TheCadien\Bundle\SuluNewsBundle\Common\DoctrineListRepresentationFactory;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Exception\NewsException;
use TheCadien\Bundle\SuluNewsBundle\Service\News\NewsService;

#[AsController]
final class NewsController extends AbstractController
{
    public function __construct(
        private readonly NewsService $newsService,
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        private readonly NewsApiDtoFactory $apiDtoFactory
    ) {
    }

    #[Route('/news', name: 'app.cget_news', methods: ['GET'])]
    public function cget(Request $request): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            News::RESOURCE_KEY,
            [],
            ['locale' => $request->query->get('locale')]
        );

        return $this->json($listRepresentation->toArray());
    }

    #[Route('/news/{id}', name: 'app.get_news', methods: ['GET'])]
    public function get(News $news, Request $request): Response
    {
        return $this->json($this->apiDtoFactory->generate($news, $request->query->get('locale')));
    }

    #[Route('/news', name: 'app.post_news', methods: ['POST'])]
    public function post(#[MapRequestPayload(acceptFormat: 'json')] NewsApi $newsApi, Request $request): Response
    {
        try {
            $news = $this->newsService->saveNewNews($newsApi, $request->query->get('locale'));
        } catch (NewsException) {
            throw new NewsException();
        }

        return $this->json($this->apiDtoFactory->generate($news, $request->query->get('locale')), 200, [], ['fullNews']);
    }

    #[Route('/news/{id}', name: 'app.post_news_trigger', methods: ['POST'])]
    public function postTrigger(News $news, Request $request): Response
    {
        try {
            $news = $this->newsService->updateNewsPublish($news, $request->query->all());
        } catch (NewsException) {
            throw new NewsException();
        }

        return $this->json($this->apiDtoFactory->generate($news, $request->query->get('locale')));
    }

    #[Route('/news/{id}', name: 'app.put_news', methods: ['PUT'])]
    public function put(News $news, #[MapRequestPayload(acceptFormat: 'json')] NewsApi $newsApi, Request $request): Response
    {
        try {
            $updatedEntity = $this->newsService->updateNews($newsApi, $news, $request->query->get('locale'));
        } catch (NewsException) {
            throw new NewsException();
        }

        return $this->json($this->apiDtoFactory->generate($updatedEntity, $request->query->get('locale')));
    }

    #[Route('/news/{id}', name: 'app.delete_news', methods: ['DELETE'])]
    public function delete(News $news): Response
    {
        try {
            $this->newsService->removeNews($news->getId());
        } catch (NewsException) {
            throw new NewsException();
        }

        return $this->json(null, 204);
    }
}
