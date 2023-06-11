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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api;

use TheCadien\Bundle\SuluNewsBundle\Api\News as ApiNews;

/**
 * Trait NewsTrait.
 */
trait NewsTrait
{
    use \TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Entity\NewsTrait;
    
    public function generateApiNews(): ApiNews
    {
        return new ApiNews(
            $this->generateNews()->getId(),
            $this->generateNews()->getTitle(),
            $this->generateNews()->getTeaser(),
            $this->generateNews()->getContent(),
            $this->generateNews()->isEnabled(),
            $this->generateNews()->getPublishedAt(),
            $this->generateNews()->getRoute()?->getPath(),
            $this->generateNews()->getTagNameArray(),
            [
                'id' => $this->generateNews()->getHeader()?->getId(),
            ],
            $this->generateNews()->getCreated(),
            $this->generateNews()->getCreated(),
            $this->generateNews()->getChanged(),
            $this->generateNews()->getCreator()?->getId(),
            $this->generateNews()->getSeo()
        );
    }
}
