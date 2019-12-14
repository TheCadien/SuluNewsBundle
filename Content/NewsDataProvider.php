<?php

declare(strict_types=1);

namespace App\Bundle\NewsBundle\Content;

use Sulu\Component\SmartContent\Orm\BaseDataProvider;

class NewsDataProvider extends BaseDataProvider
{
    public function getConfiguration()
    {
        if (null === $this->configuration) {
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
        return array_map(
            function ($item) {
                return new NewsDataItem($item);
            },
            $data
        );
    }
}
