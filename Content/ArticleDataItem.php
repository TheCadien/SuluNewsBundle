<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Content;


use App\Bundle\ArticleBundle\Entity\Article;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\SmartContent\ItemInterface;

class ArticleDataItem implements ItemInterface
{
    /**
     * @var Article
     *
     * @Serializer\Exclude
     */
    private $entity;

    /**
     * ArticleDataItem constructor.
     * @param Article $entity
     */
    public function __construct(Article $entity)
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
     * @return Article|mixed
     */
    public function getResource()
    {
        return $this->entity;
    }
}
