<?php

namespace Functional\Controller\Admin;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class NewsControllerTest extends SuluTestCase
{
    public function testAppGetNews(): never
    {
        $client = static::createClient();
        $client->request('get', '/admin/api/news/1');

        $response = $client->getResponse();
        dd($response);
        self::assertSame('hi', 'hi');
    }
}
