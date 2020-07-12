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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Entity\Factory;

use PHPUnit\Framework\TestCase;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\MediaFactoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\NewsFactory;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\TagFactoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Entity\NewsTrait;

class NewsFactoryTest extends TestCase
{
    /**
     * @var NewsFactory
     */
    public $factory;

    use NewsTrait;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $mediaFactory = $this->prophesize(MediaFactoryInterface::class);
        $mediaFactory->generateMedia()->willReturn([]);

        $tagFactory = $this->prophesize(TagFactoryInterface::class);
        $tagFactory->processTags()->willReturn([]);

        $this->factory = new NewsFactory($mediaFactory->reveal(), $tagFactory->reveal());
    }

    public function testNewNewsFactory()
    {
        $news = $this->factory->generateNewNewsFromRequest($this->generateNewsContentArray());
        $this->assertSame('Test Title', $news->getTitle());
        $this->assertSame('Test Content', $news->getContent());
        $this->assertSame('Test Teaser', $news->getTeaser());
        $this->assertSame('2017-08-31 00:00:00', $news->getPublishedAt()->format('Y-m-d H:i:s'));
    }

    public function testUpdateNewsFactory()
    {
        $oldNews = $this->generateSecondNewsWithContent();

        $news = $this->factory->updateNewsFromRequest($this->generateNewsContentArray(),$oldNews);

        $this->assertSame('Test Title', $news->getTitle());
        $this->assertSame('Test Content', $news->getContent());
        $this->assertSame('Test Teaser', $news->getTeaser());
        $this->assertSame('2017-08-31 00:00:00', $news->getPublishedAt()->format('Y-m-d H:i:s'));
    }
}
