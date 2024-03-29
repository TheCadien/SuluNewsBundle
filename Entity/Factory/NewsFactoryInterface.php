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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use TheCadien\Bundle\SuluNewsBundle\Entity\News;

interface NewsFactoryInterface
{
    public function generateNewsFromRequest(News $news, array $data, string $locale): News;
}
