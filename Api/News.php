<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Bundle\NewsBundle\Api;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use App\Bundle\NewsBundle\Entity\News as NewsEntity;
use Sulu\Bundle\ContactBundle\Api\Contact;
use Sulu\Bundle\MediaBundle\Api\Media;
use Sulu\Bundle\TagBundle\Tag\TagInterface;
use Sulu\Component\Rest\ApiWrapper;


/**
 * The News class which will be exported to the API.
 *
 * @ExclusionPolicy("all")
 */
class News extends ApiWrapper
{
    const TYPE = 'news';


    /**
     * @var Media
     */
    private $header = null;

    /**
     * @param NewsEntity $contact
     */
    public function __construct(NewsEntity $contact, $locale)
    {
        /** @var NewsEntity entity */
        $this->entity = $contact;
        $this->locale = $locale;
    }

    /**
     * Get id.
     *
     * @return int
     *
     * @VirtualProperty
     * @SerializedName("id")
     * @Groups({"fullNews"})
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->entity->setTitle($title);

        return $this;
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("title")
     * @Groups({"fullNews"})
     */
    public function getTitle()
    {
        return $this->entity->getTitle();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("teaser")
     * @Groups({"fullNews"})
     */
    public function getTeaser()
    {
        return $this->entity->getTeaser();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("content")
     * @Groups({"fullNews"})
     */
    public function getContent()
    {
        return $this->entity->getContent();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("enabled")
     * @Groups({"fullNews"})
     */
    public function isEnabeld(): bool
    {
        return $this->entity->isEnabled();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("published_at")
     * @Groups({"fullNews"})
     */
    public function getPublishedAt()
    {
        return $this->entity->getPublishedAt();
    }



    /**
     * Sets the avatar (media-api object).
     *
     * @param Media $header
     */
    public function setAvatar(Media $header)
    {
        $this->header = $header;
    }
    /**
     * Add tag.
     *
     * @param TagInterface $tag
     *
     * @return News
     */
    public function addTag(TagInterface $tag)
    {
        $this->entity->addTag($tag);

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param TagInterface $tag
     */
    public function removeTag(TagInterface $tag): void
    {
        $this->entity->removeTag($tag);
    }
    /**
     * Get tags.
     *
     * @return array
     *
     * @VirtualProperty
     * @SerializedName("tags")
     * @Groups({"fullNews"})
     */
    public function getTags(): array
    {
        return $this->entity->getTagNameArray();
    }

    /**
     * Get the contacts avatar and return the array of different formats.
     *
     * @return array
     *
     * @VirtualProperty
     * @SerializedName("header")
     * @Groups({"fullNews"})
     */
    public function getHeader(): array
    {
        if ($this->header) {
            return [
                'id' => $this->header->getId(),
                'url' => $this->header->getUrl(),
                'thumbnails' => $this->header->getFormats(),
            ];
        }
    }
}
