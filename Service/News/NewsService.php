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

namespace App\Bundle\NewsBundle\Service\News;

use App\Bundle\NewsBundle\Entity\Factory\NewsFactory;
use App\Bundle\NewsBundle\Entity\News;
use App\Bundle\NewsBundle\Repository\NewsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NewsService
{
    /**
     * @var NewsRepository
     */
    private $articleRepository;
    /**
     * @var NewsFactory
     */
    private $factory;

    /**
     * @var object|string
     */
    private $loginUser;

    /**
     * ArticleService constructor.
     */
    public function __construct(
        NewsRepository $articleRepository,
        NewsFactory $factory,
        TokenStorageInterface $tokenStorage
    ) {
        $this->articleRepository = $articleRepository;
        $this->factory = $factory;
        if ($tokenStorage->getToken()) {
            $this->loginUser = $tokenStorage->getToken()->getUser();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNewNews(array $data): News
    {
        try {
            $article = $this->factory->generateNewNewsFromRequest($data);
        } catch (\Exception $e) {
        }

        /* @var News $article */
        $article->setCreator($this->loginUser);
        $article->setChanger($this->loginUser);

        $this->articleRepository->save($article);

        return $article;
    }

    /**
     * @param $data
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateNews($data, News $article): News
    {
        try {
            $article = $this->factory->updateNewsFromRequest($data, $article);
        } catch (\Exception $e) {
        }
        $article->setChanger($this->loginUser);

        $this->articleRepository->save($article);

        return $article;
    }
}
