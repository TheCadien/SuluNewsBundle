<?php
namespace App\Bundle\NewsBundle\Tests\Traits;

use App\Bundle\NewsBundle\Entity\News;


/**
 * Trait NewsTrait
 * @package App\Bundle\NewsBundle\Tests\Traits
 */
trait NewsTrait
{
    /**
     * @return News
     */
    public function generateEmptyNews() : News
    {
        return new News();
    }
}
