<?php

declare(strict_types=1);

namespace App\Bundle\NewsBundle\Controller\Admin;

use App\Bundle\NewsBundle\Admin\DoctrineListRepresentationFactory;
use App\Bundle\NewsBundle\Entity\News;
use App\Bundle\NewsBundle\Api\News as NewsApi;
use App\Bundle\NewsBundle\Repository\NewsRepository;
use App\Bundle\NewsBundle\Service\News\NewsService;
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
        'fullNews'
    ];

    /**
     * NewsController constructor.
     * @param ViewHandlerInterface $viewHandler
     * @param TokenStorageInterface $tokenStorage
     * @param NewsRepository $repository
     * @param NewsService $newsService
     * @param DoctrineListRepresentationFactory $doctrineListRepresentationFactory
     * @param MediaManagerInterface $mediaManager
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

    /**
     * @param Request $request

     * @return Response
     */
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

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function getAction(int $id, Request $request): Response
    {
        /** @var News $entity */
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $apiEntity = $this->generateApiNewsEntity($entity,$this->getLocale($request));

        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAction(Request $request): Response
    {
        $news = $this->newsService->saveNewNews($request->request->all());

        $apiEntity = $this->generateApiNewsEntity($news,$this->getLocale($request));

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

        $apiEntity = $this->generateApiNewsEntity($news,$this->getLocale($request));
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function putAction(int $id, Request $request): Response
    {
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $updatedEntity = $this->newsService->updateNews($request->request->all(),$entity);
        $apiEntity = $this->generateApiNewsEntity($updatedEntity,$this->getLocale($request));
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(int $id): Response
    {
        $this->repository->remove($id);

        return $this->handleView($this->view());
    }



    /**
     * @param News $entity
     * @param string $locale
     * @return NewsApi
     */
    protected function generateApiNewsEntity(News $entity, string $locale): NewsApi
    {
        $apiEntity = new NewsApi($entity,$locale);
        if($entity->getHeader()){
            $apiEntity->setAvatar($this->mediaManager->getById($entity->getHeader()->getId(), 'de'));
        }

        return $apiEntity;
    }

    /**
     * @param NewsApi $entity
     * @return View
     */
    protected function generateViewContent(NewsApi $entity): View
    {
        $view = $this->view($entity);
        $context = new Context();
        $context->setGroups(static::$contactSerializationGroups);
        return $view->setContext($context);
    }

}
