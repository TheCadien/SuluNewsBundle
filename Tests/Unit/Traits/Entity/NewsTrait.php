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

namespace TheCadien\Bundle\SuluNewsBundle\Tests\Unit\Traits\Entity;

use DateTime;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * Trait NewsTrait.
 */
trait NewsTrait
{
    public function generateEmptyNews(): News
    {
        return new News();
    }

    public function generateNewsWithContent(): News
    {
        $news = new News();
        $contentArray = $this->generateNewsContentArray();

        $news->setId($contentArray['id']);
        $news->setTitle($contentArray['title']);
        $news->setTeaser($contentArray['teaser']);
        $news->setContent($contentArray['content']);
        $news->setEnabled($contentArray['enable']);
        $news->setPublishedAt(DateTime::createFromFormat('Y-m-d H:i:s', $contentArray['publishedAt']));

        return $news;
    }

    public function generateNewsContentArray(): array
    {
        return [
            'id' => 1,
            'title' => 'Test Title',
            'teaser' => 'Test Teaser',
            'content' => 'Test Content',
            'enable' => true,
            'publishedAt' => '2017-08-31 00:00:00',
        ];
    }

    public function generateSecondNewsWithContent(): News
    {
        $news = new News();
        $contentArray = $this->generateSecondNewsContentArray();

        $news->setId($contentArray['id']);
        $news->setTitle($contentArray['title']);
        $news->setTeaser($contentArray['teaser']);
        $news->setContent($contentArray['content']);
        $news->setEnabled($contentArray['enable']);
        $news->setPublishedAt(DateTime::createFromFormat('Y-m-d H:i:s', $contentArray['publishedAt']));

        return $news;
    }

    public function generateSecondNewsContentArray(): array
    {
        return [
            'id' => 2,
            'title' => 'Test',
            'teaser' => 'Test',
            'content' => 'Test',
            'enable' => true,
            'publishedAt' => '2017-08-31 00:00:00',
        ];
    }
}
