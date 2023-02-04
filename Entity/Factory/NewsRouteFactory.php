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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\RouteBundle\Manager\RouteManager;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsRouteFactory implements NewsRouteFactoryInterface
{
    /**
     * NewsFactory constructor.
     */
    public function __construct(private readonly RouteManager $routeManager)
    {
    }

    public function generateNewsRoute(News $news): RouteInterface
    {
        return $this->routeManager->create($news);
    }

    public function updateNewsRoute(News $news, string $routePath): RouteInterface
    {
        return $this->routeManager->update($news, $routePath);
    }
}
