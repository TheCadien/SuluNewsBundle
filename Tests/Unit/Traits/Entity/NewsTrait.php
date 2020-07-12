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

        $news->setId(1);
        $news->setTitle('Test Title');
        $news->setTeaser('Test Teaser');
        $news->setContent('Test Content');
        $news->setEnabled(true);
        $news->setPublishedAt(new \DateTime('now'));

        return $news;
    }
}
