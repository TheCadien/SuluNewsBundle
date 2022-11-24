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
use Sulu\Bundle\ContactBundle\Entity\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\MediaFactoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\NewsFactory;
use TheCadien\Bundle\SuluNewsBundle\Entity\Factory\TagFactoryInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Entity\NewsTrait;

/**
 * @internal
 * @coversNothing
 */
final class NewsFactoryTest extends TestCase
{
    use NewsTrait;

    /**
     * @var NewsFactory
     */
    public $factory;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $mediaFactory = $this->prophesize(MediaFactoryInterface::class);
        $mediaFactory->generateMedia()->willReturn([]);

        $tagFactory = $this->prophesize(TagFactoryInterface::class);
        $tagFactory->processTags()->willReturn([]);

        $contactRepository = $this->prophesize(ContactRepositoryInterface::class);
        $contactRepository->find()->willReturn(new Contact());

        $this->factory = new NewsFactory($mediaFactory->reveal(), $tagFactory->reveal(), $contactRepository->reveal());
    }

    public function testNewNewsFactory(): void
    {
        $news = $this->factory->generateNewsFromRequest(new news(), $this->generateNewsContentArray());
        static::assertSame('Test Title', $news->getTitle());
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
        static::assertSame('Test Teaser', $news->getTeaser());
        // static::assertInstanceOf(Contact::class, $news->getCreator());
        static::assertSame('2017-08-31 00:00:00', $news->getPublishedAt()->format('Y-m-d H:i:s'));
    }

    public function testNewNewsFactoryWithEmptyContent(): void
    {
        $news = $this->factory->generateNewsFromRequest(new news(), $this->generateNewsContentArrayWithOutContent());
        static::assertSame('Test', $news->getTitle());
        static::assertSame([], $news->getContent());
        static::assertSame('Test', $news->getTeaser());
        static::assertSame('2017-08-31 00:00:00', $news->getPublishedAt()->format('Y-m-d H:i:s'));
    }
}
