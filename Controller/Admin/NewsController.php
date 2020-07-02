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

namespace TheCadien\Bundle\SuluNewsBundle\Controller\Admin;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TheCadien\Bundle\SuluNewsBundle\Admin\DoctrineListRepresentationFactory;
use TheCadien\Bundle\SuluNewsBundle\Api\News as NewsApi;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;
use TheCadien\Bundle\SuluNewsBundle\Service\News\NewsService;

class NewsController extends AbstractRestController implements ClassResourceInterface
{
    /**
     * @var NewsRepository
     */
    private $repository;

    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @var DoctrineListRepresentationFactory
     */
    private $doctrineListRepresentationFactory;
    /**
     * @var MediaManagerInterface
     */
    private $mediaManager;

    // serialization groups for contact
    protected static $contactSerializationGroups = [
        'partialMedia',
        'fullNews',
    ];

    /**
     * NewsController constructor.
     */
    public function __construct(
        ViewHandlerInterface $viewHandler,
        TokenStorageInterface $tokenStorage,
        NewsRepository $repository,
        NewsService $newsService,
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        MediaManagerInterface $mediaManager
    ) {
        parent::__construct($viewHandler, $tokenStorage);

        $this->repository = $repository;
        $this->newsService = $newsService;
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;
        $this->mediaManager = $mediaManager;
    }

    public function cgetAction(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            News::RESOURCE_KEY,
            [],
            ['locale' => $locale]
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id, Request $request): Response
    {
        /** @var News $entity */
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $apiEntity = $this->generateApiNewsEntity($entity, $this->getLocale($request));

        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAction(Request $request): Response
    {
        $news = $this->newsService->saveNewNews($request->request->all());

        $apiEntity = $this->generateApiNewsEntity($news, $this->getLocale($request));

        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/news/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $news = $this->repository->findById($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }

        switch ($request->query->get('action')) {
            case 'enable':
                $news->setEnabled(true);
                break;
            case 'disable':
                $news->setEnabled(false);
                break;
        }

        $this->repository->save($news);

        $apiEntity = $this->generateApiNewsEntity($news, $this->getLocale($request));
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function putAction(int $id, Request $request): Response
    {
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $updatedEntity = $this->newsService->updateNews($request->request->all(), $entity);
        $apiEntity = $this->generateApiNewsEntity($updatedEntity, $this->getLocale($request));
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(int $id): Response
    {
        $this->repository->remove($id);

        return $this->handleView($this->view());
    }

    protected function generateApiNewsEntity(News $entity, string $locale): NewsApi
    {
        $apiEntity = new NewsApi($entity, $locale);
        if ($entity->getHeader()) {
            $apiEntity->setAvatar($this->mediaManager->getById($entity->getHeader()->getId(), 'de'));
        }

        return $apiEntity;
    }

    protected function generateViewContent(NewsApi $entity): View
    {
        $view = $this->view($entity);
        $context = new Context();
        $context->setGroups(static::$contactSerializationGroups);

        return $view->setContext($context);
    }
}
