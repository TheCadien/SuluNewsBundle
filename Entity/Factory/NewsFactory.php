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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Sulu\Bundle\TagBundle\Tag\TagManagerInterface;
use Sulu\Component\Persistence\RelationTrait;
use Sulu\Component\Rest\Exception\EntityNotFoundException;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsFactory
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
     * NewsFactory constructor.
     */
    public function __construct(
        MediaRepositoryInterface $mediaRepository,
        TagManagerInterface $tagManager
    ) {
        $this->mediaRepository = $mediaRepository;
        $this->tagManager = $tagManager;
    }

    /**
     * @throws \Exception
     */
    public function generateNewNewsFromRequest(array $data): News
    {
        $news = new News();

        if ($this->getProperty($data, 'title')) {
            $news->setTitle($this->getProperty($data, 'title'));
        }

        if ($this->getProperty($data, 'teaser')) {
            $news->setTeaser($this->getProperty($data, 'teaser'));
        }

        if ($this->getProperty($data, 'header')) {
            $news->setHeader($this->generateMedia($data['header']));
        }

        if ($this->getProperty($data, 'published_at')) {
            $news->setPublishedAt(new \DateTime($this->getProperty($data, 'published_at')));
        }

        if ($this->getProperty($data, 'content')) {
            $news->setContent($this->getProperty($data, 'content'));
        }

        if ($tags = $this->getProperty($data, 'tags')) {
            $this->processTags($news, $tags);
        }

        $news->setDate(new \DateTime());

        return $news;
    }

    /**
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function updateNewsFromRequest(array $data, News $news): News
    {
        if ($this->getProperty($data, 'title')) {
            $news->setTitle($this->getProperty($data, 'title'));
        }

        if ($this->getProperty($data, 'teaser')) {
            $news->setTeaser($this->getProperty($data, 'teaser'));
        }

        if ($this->getProperty($data, 'header')) {
            $news->setHeader($this->generateMedia($data['header']));
        }

        if ($this->getProperty($data, 'published_at')) {
            $news->setPublishedAt(new \DateTime($this->getProperty($data, 'published_at')));
        }

        if ($this->getProperty($data, 'content')) {
            $news->setContent($this->getProperty($data, 'content'));
        }

        if ($tags = $this->getProperty($data, 'tags')) {
            $this->processTags($news, $tags);
        }

        return $news;
    }

    /**
     * @param $header
     *
     * @throws EntityNotFoundException
     *
     * @return \Sulu\Bundle\MediaBundle\Entity\Media|null
     */
    private function generateMedia($header)
    {
        $mediaEntity = null;
        if (\is_array($header) && $this->getProperty($header, 'id')) {
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
        if (\array_key_exists($key, $data)) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param $tags
     *
     * @return bool
     */
    public function processTags(News $news, $tags)
    {
        $get = function ($tag) {
            return $tag->getId();
        };

        $delete = function ($tag) use ($news) {
            return $news->removeTag($tag);
        };

        $update = function () {
            return true;
        };

        $add = function ($tag) use ($news) {
            return $this->addTag($news, $tag);
        };

        $entities = $news->getTags();

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
     * @param $data
     *
     * @return bool True if there was no error, otherwise false
     */
    protected function addTag(News $news, $data)
    {
        $success = true;
        $resolvedTag = $this->getTagManager()->findOrCreateByName($data);
        $news->addTag($resolvedTag);

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
