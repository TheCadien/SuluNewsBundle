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

namespace TheCadien\Bundle\SuluNewsBundle\Repository;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * Class NewsRepository.
 */
class NewsRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(News $news): void
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    public function getPublishedNews(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('n')
            ->from('NewsBundle:News', 'n')
            ->where('n.enabled = 1')
            ->andWhere('n.publishedAt <= :created')
            ->setParameter('created', \date('Y-m-d H:i:s'))
            ->orderBy('n.publishedAt', 'DESC');

        return $qb->getQuery()->getResult();
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
     * @throws ORMException
     * @throws OptimisticLockException
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

    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        return $this->getPublishedNews();
    }
}
