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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Api;

use PHPUnit\Framework\TestCase;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

/**
 * @internal
 *
 * @coversNothing
 */
final class NewsApiTest extends TestCase
{
    use NewsTrait;

    public function testApiDto(): void
    {
        $apiDto = $this->generateApiNews();

        static::assertSame(1, $apiDto->id);
        static::assertSame('Test Title', $apiDto->title);
        static::assertSame('Test Teaser', $apiDto->teaser);
        static::assertSame(
            [
                [
                    'type' => 'title',
                    'title' => 'Test',
                ],
                [
                    'type' => 'editor',
                    'text' => '<p>Test Editor</p>',
                ],
            ],
            $apiDto->content
        );
        static::assertSame('2031-08-31 00:00:00', $apiDto->publishedAt->format('Y-m-d H:i:s'));
        static::assertSame('/test-1', $apiDto->route);
    }
}
