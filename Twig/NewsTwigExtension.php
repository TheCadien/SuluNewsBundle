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

namespace TheCadien\Bundle\SuluNewsBundle\Twig;

use Doctrine\Common\Cache\Cache;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension to handle news in frontend.
 */
class NewsTwigExtension extends AbstractExtension
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache, NewsRepository $newsRepository)
    {
        $this->cache = $cache;
        $this->newsRepository = $newsRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('sulu_resolve_news', [$this, 'resolveNewsFunction']),
        ];
    }

    /**
     * @param int $id id to resolve
     */
    public function resolveNewsFunction($id)
    {
        if ($this->cache->contains($id)) {
            return $this->cache->fetch($id);
        }

        $news = $this->newsRepository->find($id);
        if (null === $news) {
            return;
        }

        $this->cache->save($id, $news);

        return $news;
    }
}
