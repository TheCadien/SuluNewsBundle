<?php
namespace App\Bundle\ArticleBundle\Repository;

use App\Bundle\ArticleBundle\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;

/**
 * Class ArticleRepository
 * @package App\Bundle\ArticleBundle\Repository
 */
class ArticleRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{

    /**
     * ArticleRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param Article $article
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Article $article): void
    {
        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush();
    }

    public function getPublishedArticles()
    {
        $articles = $this->findBy(['enabled' => 1]);
        if (!$articles) {
            return null;
        }

        return $articles;
    }

    /**
     * @param int $id
     * @param string $locale
     * @return Article|null
     */
    public function findById(int $id): ?Article
    {
        $article = $this->find($id);
        if (!$article) {
            return null;
        }

        return $article;
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