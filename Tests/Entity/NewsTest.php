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

namespace Tchibo\ApiBundle\Tests\DataTransferObject;

use PHPUnit\Framework\TestCase;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Tests\Traits\NewsTrait;

class NewsTest extends TestCase
{
    use NewsTrait;

    /**
     * Test getter and Setter of the DTO.
     */
    public function testObjectGetterAndSetter()
    {
        /** @var News $news */
        $news = $this->generateEmptyNews();

        $this->assertNull($news->getId());
        $this->assertNull($news->getContent());
        $this->assertNull($news->getTitle());
        $this->assertNull($news->getPublishedAt());
        $this->assertNull($news->getDate());
        $this->assertFalse($news->isEnabled());
    }
}
