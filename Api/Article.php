<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Bundle\ArticleBundle\Api;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use App\Bundle\ArticleBundle\Entity\Article as ArticleEntity;
use Sulu\Bundle\MediaBundle\Api\Media;
use Sulu\Component\Rest\ApiWrapper;


/**
 * The Article class which will be exported to the API.
 *
 * @ExclusionPolicy("all")
 */
class Article extends ApiWrapper
{
    const TYPE = 'article';


    /**
     * @var Media
     */
    private $header = null;

    /**
     * @param ArticleEntity $contact
     */
    public function __construct(ArticleEntity $contact, $locale)
    {
        /** @var ArticleEntity entity */
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
     * @Groups({"fullArticle"})
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
     * @Groups({"fullArticle"})
     */
    public function getTitle()
    {
        return $this->entity->getTitle();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("teaser")
     * @Groups({"fullArticle"})
     */
    public function getTeaser()
    {
        return $this->entity->getTeaser();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("content")
     * @Groups({"fullArticle"})
     */
    public function getContent()
    {
        return $this->entity->getContent();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("enabled")
     * @Groups({"fullArticle"})
     */
    public function isEnabeld()
    {
        return $this->entity->isEnabled();
    }

    /**
     *
     * @VirtualProperty
     * @SerializedName("published_at")
     * @Groups({"fullArticle"})
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
     * Get the contacts avatar and return the array of different formats.
     *
     * @return array
     *
     * @VirtualProperty
     * @SerializedName("header")
     * @Groups({"fullArticle"})
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


    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getLastName(),
            'firstName' => $this->getFirstName(),
            'middleName' => $this->getMiddleName(),
            'lastName' => $this->getLastName(),
            'title' => $this->getTitle(),
            'position' => $this->getPosition(),
            'birthday' => $this->getBirthday(),
            'created' => $this->getCreated(),
            'changed' => $this->getChanged(),
        ];
    }
}
