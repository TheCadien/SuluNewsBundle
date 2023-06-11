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
        $news = $this->generateNews();

        static::assertSame($news->getId(), 1);
        static::assertSame($news->getTitle(), 'Test Title');
        static::assertSame($news->getTeaser(), 'Test Teaser');
        static::assertSame([
            [
                'type' => 'title',
                'title' => 'Test',
            ],
            [
                'type' => 'editor',
                'text' => '<p>Test Editor</p>',
            ],
        ], $news->getContent());
        static::assertSame('2031-08-31 00:00:00', $news->getPublishedAt()->format('Y-m-d H:i:s'));
    }
}
