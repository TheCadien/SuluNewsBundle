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
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

class ApiTest extends TestCase
{

    use NewsTrait;

    public function testEmptyApiDto()
    {
        $apiDto = $this->generateEmptyApiNews();

        $this->assertNull($apiDto->getId());
        $this->assertNull($apiDto->getTitle());
        $this->assertNull($apiDto->getTeaser());
        $this->assertSame([],$apiDto->getHeader());
        $this->assertNull($apiDto->getContent());
        $this->assertNull($apiDto->getPublishedAt());
        $this->assertSame([],$apiDto->getTags());

    }
}
