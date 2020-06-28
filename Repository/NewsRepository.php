<?php
namespace App\Bundle\NewsBundle\Repository;

use App\Bundle\NewsBundle\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;


/**
 * Class NewsRepository
 * @package App\Bundle\NewsBundle\Repository
 */
class NewsRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{

    /**
     * NewsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param News $news
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(News $news): void
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array
     */
    public function getPublishedNews() : array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select( 'a' )
            ->from( 'News',  'a' )
            ->where('a.enabled = 1')
            ->andWhere('a.published_at <= :date')
            ->setParameter('date',date('Y-m-d H:i:s'))
            ->orderBy('a.published_at', 'DESC');

        $news = $qb->getQuery()->getResult();


        if (!$news) {
            return [];
        }

        return $news;
    }

    /**
     * @param int $id
     * @param string $locale
     * @return News|null
     */
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
     * @param int $id
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