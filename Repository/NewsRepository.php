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

namespace TheCadien\Bundle\SuluNewsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * Class NewsRepository.
 */
class NewsRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    /**
     * NewsRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(News $news): void
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    public function getPublishedNews(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('News', 'a')
            ->where('a.enabled = 1')
            ->andWhere('a.published_at <= :created')
            ->setParameter('created', date('Y-m-d H:i:s'))
            ->orderBy('a.published_at', 'DESC');

        $news = $qb->getQuery()->getResult();

        if (!$news) {
            return [];
        }

        return $news;
    }

    public function findById(int $id): ?News
    {
        $news = $this->find($id);
        if (!$news) {
            return null;
        }

        return $news;
    }

    /**
     * Returns filtered entities.
     * When pagination is active the result count is pageSize + 1 to determine has next page.
     *
     * @param array $filters array of filters: tags, tagOperator
     * @param int $page
     * @param int $pageSize
     * @param int $limit
     * @param $locale
     * @param $options
     *
     * @return object[]
     */
    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        // TODO: Implement findByFilters() method.
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(int $id): void
    {
        $this->getEntityManager()->remove(
            $this->getEntityManager()->getReference(
                $this->getClassName(),
                $id
            )
        );
        $this->getEntityManager()->flush();
    }
}
