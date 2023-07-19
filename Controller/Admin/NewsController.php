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

use Sulu\Component\Rest\Exception\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use TheCadien\Bundle\SuluNewsBundle\Api\Factory\NewsApiDtoFactory;
use TheCadien\Bundle\SuluNewsBundle\Api\News as NewsApi;
use TheCadien\Bundle\SuluNewsBundle\Common\DoctrineListRepresentationFactory;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;
use TheCadien\Bundle\SuluNewsBundle\Service\News\NewsService;

#[AsController]
final class NewsController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $repository,
        private readonly NewsService $newsService,
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        private readonly NewsApiDtoFactory $apiDtoFactory
    ) {
    }

    #[Route('/news', name: 'app.cget_news', methods: ['GET'])]
    public function cget(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            News::RESOURCE_KEY,
            [],
            ['locale' => $locale]
        );

        return $this->json($listRepresentation->toArray());
    }

    #[Route('/news/{id}', name: 'app.get_news', methods: ['GET'])]
    public function get(int $id, Request $request): Response
    {
        if (!($entity = $this->repository->findById($id)) instanceof \TheCadien\Bundle\SuluNewsBundle\Entity\News) {
            throw new NotFoundHttpException();
        }
        $apiEntity = $this->generateApiNewsEntity($entity, $request->query->get('locale'));

        return $this->json($apiEntity, 200, []);
    }

    #[Route('/news', name: 'app.post_news', methods: ['POST'])]
    public function post(Request $request): Response
    {
        $news = $this->newsService->saveNewNews($request->request->all(), $request->query->get('locale'));

        $apiEntity = $this->generateApiNewsEntity($news, $request->query->get('locale'));

        return $this->json($apiEntity, 200, [], ['fullNews']);
    }

    #[Route('/news/{id}', name: 'app.post_news_trigger', methods: ['POST'])]
    public function postTrigger(int $id, Request $request): Response
    {
        $news = $this->repository->findById($id);
        if (!$news instanceof News) {
            throw new NotFoundHttpException();
        }

        $news = $this->newsService->updateNewsPublish($news, $request->query->all());

        $apiEntity = $this->generateApiNewsEntity($news, $request->query->get(
            'locale'
        ));

        return $this->json($apiEntity);
    }

    #[Route('/news/{id}', name: 'app.put_news', methods: ['PUT'])]
    public function put(int $id, Request $request): Response
    {
        $entity = $this->repository->findById($id);
        if (!$entity instanceof News) {
            throw new NotFoundHttpException();
        }

        $updatedEntity = $this->newsService->updateNews($request->request->all(), $entity, $request->query->get('locale'));
        $apiEntity = $this->generateApiNewsEntity($updatedEntity, $request->query->get('locale'));

        return $this->json($apiEntity);
    }

    #[Route('/news/{id}', name: 'app.delete_news', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        try {
            $this->newsService->removeNews($id);
        } catch (\Exception) {
            throw new EntityNotFoundException(News::class, $id);
        }

        return $this->json(null, 204);
    }

    protected function generateApiNewsEntity(News $entity, string $locale): NewsApi
    {
        return $this->apiDtoFactory->generate($entity);
    }

    public function getPriority(): int
    {
        return 0;
    }
}
