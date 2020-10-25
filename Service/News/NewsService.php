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
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\NewsRouteFactory;
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
    private $newsFactory;

    /**
     * @var object|string
     */
    private $loginUser;

    /**
     * @var NewsRouteFactory
     */
    private $routeFactory;

    /**
     * ArticleService constructor.
     */
    public function __construct(
        NewsRepository $newsRepository,
        NewsFactory $newsFactory,
        NewsRouteFactory $routeFactory,
        TokenStorageInterface $tokenStorage
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        $this->routeFactory = $routeFactory;

        if ($tokenStorage->getToken()) {
            $this->loginUser = $tokenStorage->getToken()->getUser();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNewNews(array $data, string $locale): News
    {
        try {
            $news = $this->newsFactory->generateNewsFromRequest(new News(), $data, $locale);
        } catch (\Exception $e) {
        }

        /* @var News $news */
        $news->setCreator($this->loginUser);
        $news->setChanger($this->loginUser);

        $this->newsRepository->save($news);

        $route = $this->routeFactory->generateNewsRoute($news);
        $news->setRoute($route);
        $this->newsRepository->save($news);

        return $news;
    }

    /**
     * @param $data
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateNews($data, News $news, string $locale): News
    {
        try {
            $news = $this->newsFactory->generateNewsFromRequest($news, $data, $locale);
        } catch (\Exception $e) {
        }

        $news->setChanger($this->loginUser);

        if ($news->getRoute()->getPath() !== $data['route']) {
            $route = $this->routeFactory->updateNewsRoute($news, $data['route']);
            $news->setRoute($route);
        }

        $this->newsRepository->save($news);

        return $news;
    }

    public function updateNewsPublish(News $news, array $data): News
    {
        switch ($data['action']) {
            case 'enable':
                $news = $this->newsFactory->generateNewsFromRequest($news, [], null, true);
                break;
            case 'disable':
                $news = $this->newsFactory->generateNewsFromRequest($news, [], null, false);
                break;
        }
        $this->newsRepository->save($news);

        return $news;
    }
}
