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

namespace TheCadien\Bundle\SuluNewsBundle\Content;

use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\SmartContent\ItemInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsDataItem implements ItemInterface
{
    /**
     * NewsDataItem constructor.
     */
    public function __construct(
        /**
         * @Serializer\Exclude
         */
        private readonly News $entity
    ) {
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
     * @return mixed|News
     */
    public function getResource()
    {
        return $this->entity;
    }
}
