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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Api;

use PHPUnit\Framework\TestCase;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

class NewsApiTest extends TestCase
{
    use NewsTrait;

    public function testEmptyApiDto()
    {
        $apiDto = $this->generateEmptyApiNews();

        $this->assertInstanceOf(News::class, $apiDto->getEntity());

        $this->assertNull($apiDto->getId());
        $this->assertNull($apiDto->getTitle());
        $this->assertNull($apiDto->getTeaser());
        $this->assertSame([], $apiDto->getHeader());
        $this->assertSame([], $apiDto->getContent());
        $this->assertNull($apiDto->getPublishedAt());
        $this->assertSame([], $apiDto->getTags());
        $this->assertSame('', $apiDto->getRoute());
    }

    public function testApiDtoWithContent()
    {
        $apiDto = $this->generateApiNewsWithContent();

        $this->assertInstanceOf(News::class, $apiDto->getEntity());

        $this->assertSame(1, $apiDto->getId());
        $this->assertSame('Test Title', $apiDto->getTitle());
        $this->assertSame('Test Teaser', $apiDto->getTeaser());
        $this->assertSame(
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
            $apiDto->getContent()
        );
        $this->assertSame('2017-08-31 00:00:00', $apiDto->getPublishedAt()->format('Y-m-d H:i:s'));
        $this->assertSame('/test-1', $apiDto->getRoute());
    }
}
