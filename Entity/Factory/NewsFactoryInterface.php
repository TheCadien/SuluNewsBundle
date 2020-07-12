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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use TheCadien\Bundle\SuluNewsBundle\Entity\News;

interface NewsFactoryInterface
{
    public function generateNewNewsFromRequest(array $data): News;

    public function updateNewsFromRequest(array $data, News $news): News;
}
