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

use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

class NewsSelectionContentType extends SimpleContentType
{
    public function __construct(private readonly NewsRepository $newsRepository)
    {
        parent::__construct('news_selection', []);
    }

    /**
     * @return News[]
     */
    public function getContentData(PropertyInterface $property): array
    {
        $ids = $property->getValue();

        $news = [];
        foreach ($ids ?: [] as $id) {
            $singleNews = $this->newsRepository->findById((int) $id);
            if ($singleNews && $singleNews->isEnabled()) {
                $news[] = $singleNews;
            }
        }

        return $news;
    }

    public function getViewData(PropertyInterface $property)
    {
        return [
            'ids' => $property->getValue(),
        ];
    }
}
