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

namespace TheCadien\Bundle\SuluNewsBundle\Service\News;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\NewsFactory;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

class NewsService implements NewsServiceInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;
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
        NewsRepository $newsRepository,
        NewsFactory $factory,
        TokenStorageInterface $tokenStorage
    ) {
        $this->newsRepository = $newsRepository;
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
            $news = $this->factory->generateNewsFromRequest(new News(), $data);
        } catch (\Exception $e) {
        }

        /* @var News $news */
        $news->setCreator($this->loginUser);
        $news->setChanger($this->loginUser);

        $this->newsRepository->save($news);

        return $news;
    }

    /**
     * @param $data
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateNews($data, News $news): News
    {
        try {
            $news = $this->factory->generateNewsFromRequest($news, $data);
        } catch (\Exception $e) {
        }
        $news->setChanger($this->loginUser);

        $this->newsRepository->save($news);

        return $news;
    }
}
