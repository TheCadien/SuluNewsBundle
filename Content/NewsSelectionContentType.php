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

use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

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
