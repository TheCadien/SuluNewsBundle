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

namespace TheCadien\Bundle\SuluNewsBundle\Routing;

use Sulu\Bundle\RouteBundle\Routing\Defaults\RouteDefaultsProviderInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

class NewsRouteDefaultProvider implements RouteDefaultsProviderInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getByEntity($entityClass, $id, $locale, $object = null)
    {
        return [
            '_controller' => 'sulu_news.controller:indexAction',
            'news' => $object ?: $this->newsRepository->findById((int) $id),
        ];
    }

    public function isPublished($entityClass, $id, $locale)
    {
        // TODO!
        return true;
    }

    public function supports($entityClass)
    {
        return News::class === $entityClass;
    }
}
