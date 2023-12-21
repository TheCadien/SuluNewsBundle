<?php

namespace Functional\Controller\Admin;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Api\NewsTrait;

class NewsControllerTest extends SuluTestCase
{
    use NewsTrait;

    public function testAppGetNews()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();
        $news = $this->generateNews();

        $newsRepository = $client->getContainer()->get('TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository');
        $newsRepository->save($news);

        $client->request('GET', '/admin/api/news/' . $news->getId() . '?locale=en');

        $response = json_decode($client->getResponse()->getContent());

        self::assertSame('Test Teaser', $response->teaser);
        self::assertSame('title', $response->content[0]->type);
        self::assertSame('Test', $response->content[0]->title);
        self::assertSame('/test-1', $response->route);
        self::assertTrue($response->enabled);
        self::assertSame([], $response->tags); //todo Test with tags!
        self::assertNull($response->author); //todo ! Test Author!
        self::assertNull($response->ext); //todo ! Test ext!

    }

    public function testAppGetNewsWithoutData()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();

        $client->request('GET', '/admin/api/news/1000?locale=en');
        self::assertResponseStatusCodeSame(404);
    }
}
