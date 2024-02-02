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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sulu\Bundle\TestBundle\Testing\PurgeDatabaseTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

/**
 * @internal
 *
 * @coversNothing
 */
final class NewsRepositoryTest extends SuluTestCase
{
    use NewsTrait;
    use PurgeDatabaseTrait;

    /**
     * @var EntityManager
     */
    private EntityManagerInterface $em;

    /**
     * @var NewsRepository
     */
    private EntityRepository $newsRepository;

    protected function setUp(): void
    {
        $this->em = $this->getEntityManager();
        $this->newsRepository = $this->em->getRepository(News::class);
        $this->purgeDatabase();
    }

    public function testSave(): void
    {
        $newsTestData = $this->generateNews();
        $this->newsRepository->save($newsTestData);

        $newsResult = $this->newsRepository->findOneBy(['title' => $newsTestData->getTitle()]);

        static::assertSame($newsTestData->getTitle(), $newsResult->getTitle());
    }

    public function testGetPublishedNewsWithResult(): void
    {
        $newsTestData = $this->generateNews();
        $this->newsRepository->save($newsTestData);
        $secondNewsTestData = $this->generateSecondNewsWithContent();
        $this->newsRepository->save($secondNewsTestData);

        $result = $this->newsRepository->getPublishedNews();

        self::assertCount(1, $result);
    }

    public function testGetPublishedNewsWithEmptyDatabase(): void
    {
        $result = $this->newsRepository->getPublishedNews();

        static::assertSame([], $result);
    }

    public function testGetPublishedNewsWithoutPublishedResult(): void
    {
        /** not enabled example in   */
        $newsTestData = $this->generateNews();
        $newsTestData->setEnabled(false);
        $this->newsRepository->save($newsTestData);
        /** not published example in */
        $secondNewsTestData = $this->generateSecondNewsWithContent();
        $secondNewsTestData->setPublishedAt(new \DateTime('tomorrow'));
        $this->newsRepository->save($secondNewsTestData);

        $result = $this->newsRepository->getPublishedNews();

        static::assertSame([], $result);
    }
}
