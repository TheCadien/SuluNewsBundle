<?php


namespace App\Bundle\ArticleBundle\Entity\Factory;


use App\Bundle\ArticleBundle\Entity\Article;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Sulu\Component\Rest\Exception\EntityNotFoundException;

class ArticleFactory
{


    /**
     * @var MediaRepositoryInterface
     */
    private $mediaRepository;

    public function __construct(
        MediaRepositoryInterface $mediaRepository
    )
    {
        $this->mediaRepository = $mediaRepository;
    }



    /**
     * @return Article
     * @throws \Exception
     */
    public function generateNewArticleFromRequest(array $data): Article
    {
        $article = new Article();
        $article->setTitle($data['title']);
        $article->setHeader($this->generateMedia($data['header']));
        $article->setTeaser($data['teaser']);
        $article->setPublishedAt(new \DateTime($data['published_at']));
        $article->setDate(new \DateTime());
        $article->setContent($data['content']);

        return $article;
    }

    /**
     * @param array $data
     * @param Article $article
     * @return Article
     * @throws EntityNotFoundException
     */
    public function updateArticleFromRequest(array $data, Article $article): Article
    {
        $article->setTitle($data['title']);
        $article->setTeaser($data['teaser']);
        $article->setHeader($this->generateMedia($data['header']));
        $article->setPublishedAt(new \DateTime($data['published_at']));
        $article->setDate(new \DateTime());
        $article->setContent($data['content']);

        return $article;
    }

    /**
     * @param $header
     * @return \Sulu\Bundle\MediaBundle\Entity\Media|null
     * @throws EntityNotFoundException
     */
    private function generateMedia($header)
    {
        $mediaEntity = null;
        if (is_array($header) && $this->getProperty($header, 'id')) {
            $mediaId = $this->getProperty($header, 'id');
            $mediaEntity = $this->mediaRepository->findMediaById($mediaId);

            if (!$mediaEntity) {
                throw new EntityNotFoundException($this->mediaRepository->getClassName(), $mediaId);
            }
        }
        return $mediaEntity;
    }

    /**
     * Return property for key or given default value.
     *
     * @param array $data
     * @param string $key
     * @param string $default
     *
     * @return string|null
     */
    private function getProperty($data, $key, $default = null)
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $default;
    }
}