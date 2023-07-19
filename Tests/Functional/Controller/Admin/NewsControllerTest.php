<?php

namespace Functional\Controller\Admin;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class NewsControllerTest extends SuluTestCase
{
    public function testAppGetNews()
    {
        $client = static::createAuthenticatedClient();
        $client->request('get', '/admin/api/news/1');

        $response = $client->getResponse();
        self::assertSame('hi', 'hi');
    }
}
