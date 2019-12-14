<?php

declare(strict_types=1);

namespace App\Bundle\NewsBundle\Content;


use App\Bundle\NewsBundle\Entity\News;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\SmartContent\ItemInterface;

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
     * @param News $entity
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
