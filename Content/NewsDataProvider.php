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

namespace TheCadien\Bundle\SuluNewsBundle\Content;

use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use Sulu\Component\SmartContent\Configuration\ProviderConfigurationInterface;
use Sulu\Component\SmartContent\Orm\BaseDataProvider;

class NewsDataProvider extends BaseDataProvider
{
    public function getConfiguration()
    {
        if (!$this->configuration instanceof ProviderConfigurationInterface) {
            $this->configuration = self::createConfigurationBuilder()
                ->enableLimit()
                ->enablePagination()
                ->enableSorting(
                    [
                        ['column' => 'news_translation.title', 'title' => 'sulu_admin.title'],
                    ]
                )
                ->getConfiguration();
        }

        return parent::getConfiguration();
    }

    protected function decorateDataItems(array $data)
    {
        return \array_map(
            fn ($item) => new NewsDataItem($item),
            $data
        );
    }

    protected function getSerializationContext(): Context|SerializationContext
    {
        return parent::getSerializationContext()->setGroups(['fullNews']);
    }
}
