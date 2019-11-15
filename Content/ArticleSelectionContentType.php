<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Content;

use App\Bundle\ArticleBundle\Entity\Article;
use App\Bundle\ArticleBundle\Repository\ArticleRepository;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;

class ArticleSelectionContentType extends SimpleContentType
{
    /**
     * @var ArticleRepository
     */
    private $eventRepository;

    public function __construct(ArticleRepository $eventRepository)
    {
        parent::__construct('article_selection');

        $this->eventRepository = $eventRepository;
    }

    /**
     * @return Article[]
     */
    public function getContentData(PropertyInterface $property): array
    {
        $ids = $property->getValue();

        $articles = [];
        foreach ($ids ?: [] as $id) {
            $event = $this->eventRepository->findById((int) $id);
            if ($event && $event->isEnabled()) {
                $articles[] = $event;
            }
        }

        return $articles;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewData(PropertyInterface $property)
    {
        return $property->getValue();
    }
}
