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

namespace Functional\Admin;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class AdminBackendTest extends SuluTestCase
{
    public function testListMetadataAction(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/admin/metadata/list/news');

        $this->assertHttpStatusCode(200, $client->getResponse());
        $response = \json_decode($client->getResponse()->getContent(), null, 512, \JSON_THROW_ON_ERROR);

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
        $response = \json_decode($client->getResponse()->getContent(), null, 512, \JSON_THROW_ON_ERROR);

        $form = $response->form;

        static::assertObjectHasAttribute('title', $form);
        static::assertObjectHasAttribute('publishedAt', $form);
        static::assertObjectHasAttribute('content', $form);
        static::assertObjectHasAttribute('tags', $form);

        $schema = $response->schema;

        static::assertSame(['title', 'publishedAt'], $schema->required);
    }
}
