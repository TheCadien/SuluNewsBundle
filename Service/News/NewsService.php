<?php


namespace App\Bundle\NewsBundle\Service\News;


use App\Bundle\NewsBundle\Entity\News;
use App\Bundle\NewsBundle\Entity\Factory\NewsFactory;
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
     * @param NewsRepository $articleRepository
     * @param NewsFactory $factory
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        NewsRepository $articleRepository,
        NewsFactory $factory,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->articleRepository = $articleRepository;
        $this->factory = $factory;
        if($tokenStorage->getToken()){
            $this->loginUser = $tokenStorage->getToken()->getUser();
        }

    }

    /**
     * @param array $data
     * @return News
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNewNews(array$data) : News
    {
        try {
            $article = $this->factory->generateNewNewsFromRequest($data);
        } catch (\Exception $e) {

        }

        /** @var News $article */
        $article->setCreator($this->loginUser);
        $article->setChanger($this->loginUser);


        $this->articleRepository->save($article);

        return $article;
    }

    /**
     * @param $data
     * @param News $article
     * @return News
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateNews($data, News $article) : News
    {
        try {
            $article = $this->factory->updateNewsFromRequest($data,$article);
        } catch (\Exception $e) {

        }
        $article->setChanger($this->loginUser);

        $this->articleRepository->save($article);

        return $article;
    }



}