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

/**
 * @internal
 *
 * @coversNothing
 */
final class NewsTest extends TestCase
{
    use NewsTrait;

    public function testEmptyApiDto(): void
    {
        $news = $this->generateEmptyNews();

        static::assertNull($news->getId());
        static::assertNull($news->getTitle());
        static::assertNull($news->getTeaser());
        static::assertNull($news->getHeader());
        static::assertSame([], $news->getContent());
        static::assertNull($news->getPublishedAt());
    }
}
