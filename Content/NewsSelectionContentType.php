<?php

declare(strict_types=1);

namespace App\Bundle\NewsBundle\Content;

use App\Bundle\NewsBundle\Entity\News;
use App\Bundle\NewsBundle\Repository\NewsRepository;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;

class NewsSelectionContentType extends SimpleContentType
{
    /**
     * @var NewsRepository
     */
    private $eventRepository;

    public function __construct(NewsRepository $eventRepository)
    {
        parent::__construct('news_selection');

        $this->eventRepository = $eventRepository;
    }

    /**
     * @return News[]
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
