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

namespace TheCadien\Bundle\SuluNewsBundle\Service\News;

use TheCadien\Bundle\SuluNewsBundle\Api\News as NewsApi;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

interface NewsServiceInterface
{
    public function saveNewNews(NewsApi $data, string $locale): News;

    public function updateNews(NewsApi $data, News $article, string $locale): News;
}
