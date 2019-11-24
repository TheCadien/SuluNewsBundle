<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Controller\Admin;

use App\Bundle\ArticleBundle\Admin\DoctrineListRepresentationFactory;
use App\Bundle\ArticleBundle\Entity\Article;
use App\Bundle\ArticleBundle\Api\Article as ArticleApi;
use App\Bundle\ArticleBundle\Repository\ArticleRepository;
use App\Bundle\ArticleBundle\Service\Article\ArticleService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Component\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends RestController implements ClassResourceInterface
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var ArticleService
     */
    private $articleService;

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
        'fullArticle'
    ];

    public function __construct(
        ArticleRepository $repository,
        ArticleService $articleService,
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        MediaManagerInterface $mediaManager
    ) {
        $this->repository = $repository;
        $this->articleService = $articleService;
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
            Article::RESOURCE_KEY,
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
        /** @var Article $entity */
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $apiEntity = $this->generateApiArticleEntity($entity,$this->getLocale($request));

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
        $article = $this->articleService->saveNewArticle($request->request->all());

        $apiEntity = $this->generateApiArticleEntity($article,$this->getLocale($request));

        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/articles/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $article = $this->repository->findById($id);
        if (!$article) {
            throw new NotFoundHttpException();
        }

        switch ($request->query->get('action')) {
            case 'enable':
                $article->setEnabled(true);
                break;
            case 'disable':
                $article->setEnabled(false);
                break;
        }

        $this->repository->save($article);

        $apiEntity = $this->generateApiArticleEntity($article,$this->getLocale($request));
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
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $updatedEntity = $this->articleService->updateArticle($request->request->all(),$entity);
        $apiEntity = $this->generateApiArticleEntity($updatedEntity,$this->getLocale($request));
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
        $this->remove($id);

        return $this->handleView($this->view());
    }

    /**
     * @param int $id
     *
     * TODO ! Remove in repo
     * @param Request $request
     * @return Article|null
     */
    protected function load(int $id, Request $request): ?Article
    {
        return $this->repository->findById($id);
    }

    /**
     * @param int $id
     *
     * TODO ! Remove in repo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }

    /**
     * @param Article $entity
     * @param string $locale
     * @return ArticleApi
     */
    protected function generateApiArticleEntity(Article $entity,string $locale): ArticleApi
    {
        $apiEntity = new ArticleApi($entity,$locale);
        if($entity->getHeader()){
            $apiEntity->setAvatar($this->mediaManager->getById($entity->getHeader()->getId(), 'de'));
        }

        return $apiEntity;
    }

    /**
     * @param ArticleApi $entity
     * @return View
     */
    protected function generateViewContent(ArticleApi $entity): View
    {
        $view = $this->view($entity);
        $context = new Context();
        $context->setGroups(static::$contactSerializationGroups);
        return $view->setContext($context);
    }

}
