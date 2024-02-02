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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\TagBundle\Tag\TagManagerInterface;
use Sulu\Component\Persistence\RelationTrait;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class TagFactory implements TagFactoryInterface
{
    use RelationTrait;

    /**
     * TagFactory constructor.
     */
    public function __construct(private readonly TagManagerInterface $tagManager)
    {
    }

    /**
     * @param mixed $tags
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

        $this->resetIndexOfSubentites($entities);

        return $result;
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

    /**
     * Adds a new tag to the given contact and persist it with the given object manager.
     *
     * @return bool True if there was no error, otherwise false
     */
    protected function addTag(News $news, mixed $data): bool
    {
        $resolvedTag = $this->getTagManager()->findOrCreateByName($data);
        $news->addTag($resolvedTag);

        return true;
    }
}
