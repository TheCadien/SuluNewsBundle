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

namespace App\Bundle\NewsBundle\Tests\Traits;

use App\Bundle\NewsBundle\Entity\News;

/**
 * Trait NewsTrait.
 */
trait NewsTrait
{
    public function generateEmptyNews(): News
    {
        return new News();
    }
}
