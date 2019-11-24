<?php


namespace App\Bundle\ArticleBundle\Entity\Factory;


use App\Bundle\ArticleBundle\Entity\Article;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Sulu\Bundle\TagBundle\Tag\TagManagerInterface;
use Sulu\Component\Persistence\RelationTrait;
use Sulu\Component\Rest\Exception\EntityNotFoundException;

class ArticleFactory
{
    use RelationTrait;
    
    /**
     * @var MediaRepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var TagManagerInterface
     */
    private $tagManager;

    /**
     * ArticleFactory constructor.
     * @param MediaRepositoryInterface $mediaRepository
     * @param TagManagerInterface $tagManager
     */
    public function __construct(
        MediaRepositoryInterface $mediaRepository,
        TagManagerInterface $tagManager
    )
    {
        $this->mediaRepository = $mediaRepository;
        $this->tagManager = $tagManager;
    }



    /**
     * @return Article
     * @throws \Exception
     */
    public function generateNewArticleFromRequest(array $data): Article
    {
        $article = new Article();

        if ($this->getProperty($data, 'title')) {
            $article->setTitle($this->getProperty($data, 'title'));
        }

        if ($this->getProperty($data, 'teaser')) {
            $article->setTeaser($this->getProperty($data, 'teaser'));
        }

        if ($this->getProperty($data, 'header')) {
            $article->setHeader($this->generateMedia($data['header']));
        }

        if ($this->getProperty($data, 'published_at')) {
            $article->setPublishedAt(new \DateTime($this->getProperty($data, 'published_at')));
        }

        if ($this->getProperty($data, 'content')) {
            $article->setContent($this->getProperty($data, 'content'));
        }

        if ($tags = $this->getProperty($data, 'tags')) {
            $this->processTags($article, $tags);
        }

        $article->setDate(new \DateTime());

        return $article;
    }

    /**
     * @param array $data
     * @param Article $article
     * @return Article
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function updateArticleFromRequest(array $data, Article $article): Article
    {
        if ($this->getProperty($data, 'title')) {
            $article->setTitle($this->getProperty($data, 'title'));
        }

        if ($this->getProperty($data, 'teaser')) {
            $article->setTeaser($this->getProperty($data, 'teaser'));
        }

        if ($this->getProperty($data, 'header')) {
            $article->setHeader($this->generateMedia($data['header']));
        }

        if ($this->getProperty($data, 'published_at')) {
            $article->setPublishedAt(new \DateTime($this->getProperty($data, 'published_at')));
        }

        if ($this->getProperty($data, 'content')) {
            $article->setContent($this->getProperty($data, 'content'));
        }

        if ($tags = $this->getProperty($data, 'tags')) {
            $this->processTags($article, $tags);
        }

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

    /**
     * @param Article $article
     * @param $tags
     * @return bool
     */
    public function processTags(Article $article, $tags)
    {
        $get = function($tag) {
            return $tag->getId();
        };

        $delete = function($tag) use ($article) {
            return $article->removeTag($tag);
        };

        $update = function() {
            return true;
        };

        $add = function($tag) use ($article) {
            return $this->addTag($article, $tag);
        };

        $entities = $article->getTags();

        $result = $this->processSubEntities(
            $entities,
            $tags,
            $get,
            $add,
            $update,
            $delete
        );
        return $result;
    }

    /**
     * Adds a new tag to the given contact and persist it with the given object manager.
     *
     * @param Article $article
     * @param $data
     *
     * @return bool True if there was no error, otherwise false
     */
    protected function addTag(Article $article, $data)
    {
        $success = true;
        $resolvedTag = $this->getTagManager()->findOrCreateByName($data);
        $article->addTag($resolvedTag);

        return $success;
    }

    /**
     * Returns the tag manager.
     *
     * @return TagManagerInterface
     */
    public function getTagManager()
    {
        return $this->tagManager;
    }
}