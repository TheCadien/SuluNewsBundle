<?php

namespace Functional\Admin;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class AdminBackendTest extends SuluTestCase
{
    public function testListMetadataAction(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/admin/metadata/list/news');

        $this->assertHttpStatusCode(200, $client->getResponse());
        $response = json_decode($client->getResponse()->getContent());

        static::assertObjectHasAttribute('title', $response);
        static::assertObjectHasAttribute('publishedAt', $response);
        static::assertObjectHasAttribute('teaser', $response);
        static::assertObjectHasAttribute('enabled', $response);
        static::assertObjectHasAttribute('changed', $response);
        static::assertObjectHasAttribute('changer', $response);
        static::assertObjectHasAttribute('created', $response);
        static::assertObjectHasAttribute('creator', $response);
        static::assertObjectHasAttribute('header', $response);
        static::assertObjectHasAttribute('id', $response);
    }

    public function testFormMetadataAction(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/admin/metadata/form/news_details_add');

        $this->assertHttpStatusCode(200, $client->getResponse());
        $response = json_decode($client->getResponse()->getContent());

        $form = $response->form;

        static::assertObjectHasAttribute('title', $form);
        static::assertObjectHasAttribute('publishedAt', $form);
        static::assertObjectHasAttribute('content', $form);
        static::assertObjectHasAttribute('tags', $form);

        $schema = $response->schema;

        static::assertSame(['title', 'publishedAt'], $schema->required);
    }

}