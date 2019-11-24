<?php


namespace App\Bundle\ArticleBundle\Service\Article;


use App\Bundle\ArticleBundle\Entity\Article;
use App\Bundle\ArticleBundle\Entity\Factory\ArticleFactory;
use App\Bundle\ArticleBundle\Repository\ArticleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ArticleService
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var ArticleFactory
     */
    private $factory;

    /**
     * ArticleService constructor.
     * @param ArticleRepository $articleRepository
     * @param ArticleFactory $factory
     */
    public function __construct(ArticleRepository $articleRepository,ArticleFactory $factory)
    {
        $this->articleRepository = $articleRepository;
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return Article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNewArticle(array$data) : Article
    {
        try {
            $article = $this->factory->generateNewArticleFromRequest($data);
        } catch (\Exception $e) {

        }
        $this->articleRepository->save($article);

        return $article;
    }

    /**
     * @param $data
     * @param Article $article
     * @return Article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateArticle($data,Article $article) : Article
    {
        try {
            $article = $this->factory->updateArticleFromRequest($data,$article);
        } catch (\Exception $e) {

        }
        $this->articleRepository->save($article);

        return $article;
    }



}