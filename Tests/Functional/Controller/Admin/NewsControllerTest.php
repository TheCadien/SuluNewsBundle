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

        $client->getContainer()->get('TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository')->save($news);

        $client->request('GET', '/admin/api/news/' . $news->getId() . '?locale=en');

        $response = \json_decode($client->getResponse()->getContent());

        self::assertSame('Test Teaser', $response->teaser);
        self::assertSame('title', $response->content[0]->type);
        self::assertSame('Test', $response->content[0]->title);
        self::assertSame('/test-1', $response->route);
        self::assertTrue($response->enabled);
        self::assertSame([], $response->tags); // todo Test with tags!
        self::assertNull($response->author); // todo ! Test Author!
        self::assertNull($response->ext); // todo ! Test ext!
    }

    public function testAppGetNewsWithoutData()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();

        $client->request('GET', '/admin/api/news/1000?locale=en');
        self::assertResponseStatusCodeSame(404);
    }

    public function testPostValidNewsWithOutContent()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();

        $client->jsonRequest(
            'POST',
            '/admin/api/news?locale=en',
            [
                'title' => 'test',
                'teaser' => 'Test Teaser',
                'publishedAt' => '2023-12-23T18:22:54',
            ]
        );

        $newsResult = $client->getContainer()->get('TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository')->findOneBy(['title' => 'test']);

        self::assertSame('test', $newsResult->getTitle());
        self::assertSame('Test Teaser', $newsResult->getTeaser());
        self::assertSame('/news/' . $newsResult->getId(), $newsResult->getRoute()->getPath());
    }

    public function testNewsPostWithInvalidRequest()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();

        $client->jsonRequest(
            'POST',
            '/admin/api/news?locale=en',
            [
                'title' => '',
                'teaser' => '',
                'publishedAt' => '',
            ]
        );

        /* Symfony MapRequestPayload 422 status code */
        self::assertResponseStatusCodeSame(422);
    }

    public function testNewsPutWithValidRequest()
    {
        $client = self::createAuthenticatedClient();

        self::purgeDatabase();
        $news = $this->generateNews();

        $client->getContainer()->get('TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository')->save($news);

        $client->jsonRequest(
            'PUT',
            '/admin/api/news/' . $news->getId() . '?locale=en',
            [
                'title' => 'New Title',
                'teaser' => 'New Teaser',
                'publishedAt' => '2023-12-23T18:22:54',
                'route' => '/news/fancy-new-route',
                'content' => [
                    [
                        'type' => 'editor',
                        'text' => '<p>Test Editor</p>',
                    ],
                    [
                        'type' => 'title',
                        'title' => 'Test',
                    ],
                ],
                'tags' => [
                    'Test',
                    'Sulu',
                    'News',
                ],
            ]
        );

        $updatetNews = $client->getContainer()->get('TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository')->findById($news->getId());
        self::assertSame('New Title', $updatetNews->getTitle());
        self::assertSame('New Teaser', $updatetNews->getTeaser());
        self::assertSame('/news/fancy-new-route', $updatetNews->getRoute()->getPath());
        self::assertSame('Max', $updatetNews->getChanger()->getFirstName());
        self::assertSame('Mustermann', $updatetNews->getChanger()->getLastName());
        self::assertSame('title', $updatetNews->getContent()[1]['type']);
        self::assertSame('Test', $updatetNews->getContent()[1]['title']);
    }
}
