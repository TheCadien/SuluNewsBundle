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

namespace TheCadien\Bundle\SuluNewsBundle\Content;

use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\SmartContent\ItemInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsDataItem implements ItemInterface
{
    /**
     * @var News
     *
     * @Serializer\Exclude
     */
    private $entity;

    /**
     * NewsDataItem constructor.
     */
    public function __construct(News $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getTitle()
    {
        return $this->entity->getTitle();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getImage()
    {
        return $this->entity->getHeader();
    }

    /**
     * @return News|mixed
     */
    public function getResource()
    {
        return $this->entity;
    }
}
