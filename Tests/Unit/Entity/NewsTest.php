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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

class NewsTest extends TestCase
{
    use NewsTrait;

    public function testEmptyApiDto()
    {
        $news = $this->generateEmptyNews();

        $this->assertNull($news->getId());
        $this->assertNull($news->getTitle());
        $this->assertNull($news->getTeaser());
        $this->assertNull($news->getHeader());
        $this->assertSame([], $news->getContent());
        $this->assertNull($news->getPublishedAt());
    }
}
