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

namespace Functional\Controller;

use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

/**
 * @internal
 * @coversNothing
 */
final class AdminControllerTest extends SuluTestCase
{
    public function testTagMetadataAction(): void
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
