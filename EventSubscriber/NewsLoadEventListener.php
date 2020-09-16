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

namespace TheCadien\Bundle\SuluNewsBundle\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsLoadEventListener
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $news = $args->getObject();
        if (!$news instanceof News) {
            return;
        }

        if ($news->getHeader()) {
            try {
                $media = $this->container->get('sulu_media.media_manager')->getById($news->getHeader()->getId(), 'de');
                $news->setHeader($media);
            } catch (Exception $e) {
            }
        }

        if ($news->getRoute()) {
            $route = $this->container->get('sulu.repository.route')->findOneBy(['id' => $news->getRoute()->getId()]);
            $news->setRoute($route);
        }

        return $news;
    }
}
