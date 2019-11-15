<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Content;

use Sulu\Component\SmartContent\Orm\BaseDataProvider;

class ArticleDataProvider extends BaseDataProvider
{
    public function getConfiguration()
    {
        if (null === $this->configuration) {
            $this->configuration = self::createConfigurationBuilder()
                ->enableLimit()
                ->enablePagination()
                ->enableSorting(
                    [
                        ['column' => 'article_translation.title', 'title' => 'sulu_admin.title'],
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
                return new ArticleDataItem($item);
            },
            $data
        );
    }
}
